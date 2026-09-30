

<div id="compareBar" class="hidden fixed bottom-4 left-4 right-4 sm:left-1/2 sm:right-auto sm:-translate-x-1/2 z-[1000] bg-white border border-gray-100 rounded-2xl sm:rounded-3xl p-3 sm:p-4 shadow-[0_15px_45px_rgba(0,0,0,0.15)] flex items-center justify-between gap-3 sm:gap-6 sm:w-full sm:max-w-lg transition-all duration-300">
    
    <!-- Thumbnail Previews (Responsive gaps) -->
    <div class="flex items-center gap-1.5 sm:gap-3 overflow-x-auto scrollbar-hide" id="compareThumbs"></div>
    
    <!-- Action Buttons (Responsive sizing and padding) -->
    <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
        <button type="button" onclick="clearCompareList()" class="text-[10px] sm:text-xs text-red-500 font-extrabold hover:underline px-1 sm:px-2 py-1">Clear</button>
        
        <a id="compareBtnLink" href="#" class="bg-[#2ba351] hover:bg-[#1a285a] text-white text-[10px] sm:text-xs font-extrabold px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl uppercase tracking-wider transition-all shadow-md shadow-[#2ba351]/10 whitespace-nowrap">
            Compare Now
        </a>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let compareList = JSON.parse(localStorage.getItem('compare_projects')) || [];
    let confirmActionCallback = null; // Global state for confirmation modal

    // ── 1. Global Reusable Custom Confirmation Modal Functions ──
    window.showConfirmModal = function(options) {
        const modal = document.getElementById('confirmModal');
        const card = modal ? modal.querySelector('.scale-95') : null;
        
        const titleEl = document.getElementById('confirmModalTitle');
        const textEl = document.getElementById('confirmModalText');
        const btnEl = document.getElementById('confirmModalBtn');
        const iconWrap = document.getElementById('confirmModalIcon');

        const settings = Object.assign({
            title: 'Are you sure?',
            text: 'Do you really want to perform this action?',
            btnText: 'Yes, Remove',
            btnClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/10',
            iconHtml: '<i class="fa-solid fa-triangle-exclamation"></i>',
            iconClass: 'bg-red-50 text-red-500',
            callback: null
        }, options);

        if (modal && card) {
            titleEl.textContent = settings.title;
            textEl.textContent = settings.text;
            btnEl.textContent = settings.btnText;
            
            btnEl.className = `w-1/2 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md ${settings.btnClass}`;
            iconWrap.className = `w-16 h-16 rounded-full mx-auto flex items-center justify-center text-xl mb-4 ${settings.iconClass}`;
            iconWrap.innerHTML = settings.iconHtml;

            confirmActionCallback = settings.callback;

            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    };

    window.closeConfirmModal = function() {
        const modal = document.getElementById('confirmModal');
        const card = modal ? modal.querySelector('.scale-100') : null;

        if (modal && card) {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                confirmActionCallback = null;
            }, 300);
        }
    };

    window.executeConfirmAction = function() {
        if (typeof confirmActionCallback === 'function') {
            confirmActionCallback();
        }
        closeConfirmModal();
    };

    // ── 2. Click Event Delegation (Compare & Favorite Toggles) ──
    document.addEventListener('click', async function(e) {
        
        // A. Handle Compare Button Toggle
        const compareBtn = e.target.closest('.btn-compare-toggle');
        if (compareBtn) {
            e.preventDefault();
            const id = parseInt(compareBtn.dataset.id);
            const title = compareBtn.dataset.title;
            const image = compareBtn.dataset.image;

            const existingIndex = compareList.findIndex(item => item.id === id);

            if (existingIndex > -1) {
                compareList.splice(existingIndex, 1);
            } else {
                if (compareList.length >= 3) {
                    // [UPDATED] Replaced ugly browser alert with our gorgeous custom warning modal!
                    showConfirmModal({
                        title: 'Limit Exceeded',
                        text: 'You can compare a maximum of 3 properties at a time.',
                        btnText: 'Understand',
                        btnClass: 'bg-[#2ba351] hover:bg-[#1a285a] shadow-[#2ba351]/10',
                        iconClass: 'bg-green-50 text-[#2ba351]',
                        iconHtml: '<i class="fa-solid fa-scale-unbalanced-flip"></i>',
                        callback: null // Just closes on click
                    });
                    return;
                }
                compareList.push({ id, title, image });
            }

            localStorage.setItem('compare_projects', JSON.stringify(compareList));
            updateCompareUI();
        }

        // B. Handle AJAX Favorite/Bookmark Toggle
        const favBtn = e.target.closest('.btn-favorite-toggle');
        if (favBtn) {
            e.preventDefault();
            const projectId = favBtn.dataset.id;
            const icon = favBtn.querySelector('i');

            try {
                const response = await fetch("{{ route('projects.favorite.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ project_id: projectId })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    if (result.status === 'added') {
                        icon.className = 'fa-solid fa-heart text-red-500 text-xs pointer-events-none';
                        favBtn.setAttribute('title', 'Remove from Favorites');
                    } else {
                        icon.className = 'fa-regular fa-heart text-gray-700 text-xs pointer-events-none';
                        favBtn.setAttribute('title', 'Add to Favorites');
                    }
                } else {
                    if (response.status === 401) {
                        // [RESOLVED] This is now fully supported globally across all pages!
                        showConfirmModal({
                            title: 'Login Required',
                            text: 'Please login to save this property to your favorites list.',
                            btnText: 'Login Now',
                            btnClass: 'bg-[#2ba351] hover:bg-[#1a285a] shadow-[#2ba351]/10',
                            iconClass: 'bg-green-50 text-[#2ba351]',
                            iconHtml: '<i class="fa-solid fa-circle-user"></i>',
                            callback: function() {
                                const currentUrl = encodeURIComponent(window.location.href);
                                window.location.href = "{{ route('company.login') }}?redirect=" + currentUrl;
                            }
                        });
                    } else {
                        alert(result.message || 'Something went wrong.');
                    }
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
                alert('Connection error. Please try again.');
            }
        }
    });

    // ── 3. Render Compare Bar and update UI states ──
    function updateCompareUI() {
        const compareBar = document.getElementById('compareBar');
        const thumbsContainer = document.getElementById('compareThumbs');
        const btnLink = document.getElementById('compareBtnLink');
        
        if (!compareBar || !thumbsContainer) return;

        document.querySelectorAll('.btn-compare-toggle').forEach(btn => {
            const id = parseInt(btn.dataset.id);
            const exists = compareList.some(item => item.id === id);
            if (exists) {
                btn.classList.add('bg-[#2ba351]', 'text-white');
                btn.classList.remove('bg-white/90', 'text-gray-700');
            } else {
                btn.classList.remove('bg-[#2ba351]', 'text-white');
                btn.classList.add('bg-white/90', 'text-gray-700');
            }
        });

        if (compareList.length === 0) {
            compareBar.classList.add('hidden');
            return;
        }

        thumbsContainer.innerHTML = '';
        const idsArray = [];
        compareList.forEach(item => {
            idsArray.push(item.id);
            const thumb = document.createElement('div');
            thumb.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-lg overflow-hidden border border-gray-200 relative flex-shrink-0';
            thumb.innerHTML = `<img src="${item.image}" class="w-full h-full object-cover pointer-events-none" title="${item.title}">`;
            thumbsContainer.appendChild(thumb);
        });

        btnLink.href = `/compare?ids=${idsArray.join(',')}`;
        compareBar.classList.remove('hidden');
    }

    // ── 4. Global clear compare list function ──
    window.clearCompareList = function() {
        compareList = [];
        localStorage.removeItem('compare_projects');
        updateCompareUI();
    };

    // ── 5. Global Keyboard Shortcuts (Escape key closes confirm modal) ──
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('confirmModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        }
    });

    updateCompareUI(); // Call on page load
});
</script>
