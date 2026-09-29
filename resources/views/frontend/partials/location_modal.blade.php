
@php
    $allDestinations = \App\Models\Content::where('module', 'destination')
        ->where('status', 1)
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function($d) {
            // Count active projects belonging to this destination
            $projectCount = \App\Models\Content::where('module', 'project')
                ->where('status', 1)
                ->where('destination_id', $d->id)
                ->count();

            return [
                'id'            => $d->id,
                'title'         => $d->title,                 
                'city_area'     => $d->short ?? '',           
                'sub_area'      => $d->description ?? '',      
                'project_count' => $projectCount,
                'url'           => route('web.project', ['dest' => $d->id]) 
            ];
        });
        
    $totalGlobalProjects = \App\Models\Content::where('module', 'project')->where('status', 1)->count();
@endphp

<!-- Global Dynamic Location Picker Modal (100% Shared & Failsafe) -->
<div id="locationModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm bg-black/60 select-none">
    
    <!-- Modal Card Container -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 max-w-4xl w-full shadow-2xl flex flex-col relative z-50" style="height: 85vh; max-height: 650px;">
        
        <!-- Modal Header with Live Search -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 border-b border-gray-100 pb-4 mb-4">
            <div class="flex items-center gap-2">
                <button type="button" id="backToCitiesBtn" class="hidden text-gray-500 hover:text-[#2ba351] text-base font-bold flex items-center gap-1.5 focus:outline-none transition-colors mr-2">
                    <i class="fa-solid fa-arrow-left"></i> All Cities
                </button>
                <div class="flex flex-col">
                    <h5 id="modalHeaderTitle" class="font-black text-gray-800 text-base md:text-base uppercase tracking-wider">Select City or Area</h5>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Total Properties: {{ $totalGlobalProjects }}</span>
                </div>
            </div>

            <!-- Live Search Box -->
            <div class="relative w-full md:w-80">
                <input type="text" id="modalSearchInput" placeholder="Search city, sub-area..." 
                       class="w-full bg-gray-50 border border-gray-200 pl-10 pr-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all text-gray-700">
                <div class="absolute left-3.5 top-[70%] -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

            <button type="button" class="btn-close-location-modal text-gray-400 hover:text-red-500 text-xl focus:outline-none transition-all hidden md:block">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Scrollable Main Viewport Container -->
        <div class="flex-grow overflow-y-auto scrollbar-hide py-2" id="modalViewport">
            <!-- Dynamic Content will render here -->
        </div>

        <!-- Footer (Close button on mobile) -->
        <div class="border-t border-gray-100 pt-4 flex justify-end md:hidden">
            <button type="button" class="btn-close-location-modal w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">Close</button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Injecting mapped PHP destinations array to JS securely
    const destinations = @json($allDestinations ?? []);
    
    const modal = document.getElementById('locationModal');
    const viewport = document.getElementById('modalViewport');
    const searchInput = document.getElementById('modalSearchInput');
    const backBtn = document.getElementById('backToCitiesBtn');
    const headerTitle = document.getElementById('modalHeaderTitle');

    let uniqueCities = [];

    if (destinations && destinations.length > 0) {
        uniqueCities = [...new Set(destinations.map(d => d.city_area).filter(c => c !== ''))];
    }

    // 1. Fetch User's City via Free HTTPS Geolocation API on first landing
    if (!localStorage.getItem('user_city')) {
        fetch('https://geolocation-db.com/json/')
            .then(res => res.json())
            .then(data => {
                if (data.city) {
                    localStorage.setItem('user_city', data.city);
                    console.log("User Location Saved:", data.city);
                }
            })
            .catch(err => {
                console.warn("CORS/Geolocation API failed. Falling back to default list.", err);
            });
    }

    // 2. Global Click Event Delegation (Handles both Open, Close and Back buttons safely)
    document.addEventListener('click', function(e) {
        // A. Handle Modal Open Button Click
        if (e.target.closest('.btn-open-location-modal') || e.target.closest('[onclick="openLocationModal()"]')) {
            e.preventDefault();
            openLocationModal();
        }

        // B. Handle Modal Close Button Click
        if (e.target.closest('.btn-close-location-modal')) {
            e.preventDefault();
            closeLocationModal();
        }

        // C. Handle Back to All Cities Button Click
        if (e.target.closest('#backToCitiesBtn')) {
            e.preventDefault();
            renderAllCitiesView();
        }
    });

    // 3. Open Location Modal with Auto-Selection Match Checking
    window.openLocationModal = function() {
        if (!modal) return;
        
        modal.classList.remove('hidden');
        modal.style.display = 'flex'; // Enable flex display
        document.body.classList.add('overflow-hidden');

        const userCity = localStorage.getItem('user_city');

        // Auto-matching: Compare user_city with our city areas
        if (userCity && uniqueCities.length > 0) {
            const matchedCity = uniqueCities.find(c => c.toLowerCase() === userCity.toLowerCase());
            if (matchedCity) {
                renderSubAreasView(matchedCity); // Render matched city sub-areas directly
                console.log("Auto-matched & Selected City Area:", matchedCity);
                return;
            }
        }

        renderAllCitiesView(); // Render all alphabetical cities if no match
    };

    // 4. Close Location Modal
    window.closeLocationModal = function() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
        if (searchInput) searchInput.value = ''; // Clear search inputs on close
    };

    // 5. Render All Cities Alphabetically (Bikroy Style)
    window.renderAllCitiesView = function() {
        if (!viewport || !destinations || destinations.length === 0) return;
        
        if (backBtn) backBtn.classList.add('hidden');
        if (headerTitle) headerTitle.textContent = "Select City Area";

        const cities = [...new Set(destinations.map(d => d.city_area).filter(c => c !== ''))];
        cities.sort((a, b) => a.localeCompare(b));

        const grouped = {};
        cities.forEach(city => {
            const firstLetter = city.charAt(0).toUpperCase();
            if (!grouped[firstLetter]) grouped[firstLetter] = [];
            grouped[firstLetter].push(city);
        });

        let html = `<div class="grid grid-cols-1 md:grid-cols-3 gap-6">`;
        
        Object.keys(grouped).forEach(letter => {
            html += `<div class="flex flex-col gap-2">
                        <div class="text-xs font-black text-gray-400 border-b border-gray-150 pb-1.5 uppercase tracking-wider mb-2">${letter}</div>
                        <div class="flex flex-col gap-1">`;
            
            grouped[letter].forEach(city => {
                const cityProjectsCount = destinations.filter(d => d.city_area === city).reduce((sum, d) => sum + d.project_count, 0);

                html += `<button type="button" onclick="renderSubAreasView('${city}')" class="w-full text-left py-2 px-3 hover:bg-gray-50 rounded-xl flex items-center justify-between text-xs font-bold text-gray-700 transition-all cursor-pointer">
                            <span>${city}</span>
                            <span class="text-[10px] text-gray-400 font-semibold">${cityProjectsCount} properties</span>
                         </button>`;
            });

            html += `</div></div>`;
        });

        html += `</div>`;
        viewport.innerHTML = html;
    };

    // 6. Render Sub-Areas & Destinations for a selected City
    window.renderSubAreasView = function(cityName) {
        if (!viewport || !destinations || destinations.length === 0) return;

        if (backBtn) backBtn.classList.remove('hidden'); 
        if (headerTitle) headerTitle.textContent = `${cityName} Sub-Areas`;

        const subAreas = [...new Set(destinations.filter(d => d.city_area === cityName && d.sub_area !== '').map(d => d.sub_area))];
        subAreas.sort((a, b) => a.localeCompare(b));

        let html = `<div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-full items-start">`;
        
        const cityRedirectUrl = `{{ route('web.project') }}?city_area=${encodeURIComponent(cityName)}`;

        html += `<div class="md:col-span-4 border-r border-gray-100 pr-4 flex flex-col gap-1 max-h-[400px] overflow-y-auto scrollbar-hide">
                    <a href="${cityRedirectUrl}" class="block w-full text-left px-3 py-2.5 bg-green-50 text-[#2ba351] hover:bg-[#2ba351] hover:text-white rounded-xl text-xs font-black transition-all border border-green-150 mb-2 select-none text-center">
                        Search in All ${cityName} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ms-1"></i>
                    </a>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-3 pb-1 border-b border-gray-50">Select Sub-Area</div>`;

        subAreas.forEach(sub => {
            html += `<button type="button" onclick="filterDestinationsBySubArea('${cityName}', '${sub}', this)" class="w-full text-left py-2.5 px-3 hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-bold transition-all cursor-pointer sub-area-btn">${sub}</button>`;
        });
        html += `</div>`;

        html += `<div class="md:col-span-8">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 pb-1 border-b border-gray-50">Select Neighborhood</div>
                    <div id="destinationsSubList" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-[360px] overflow-y-auto scrollbar-hide">`;
        
        const defaultDests = destinations.filter(d => d.city_area === cityName);
        defaultDests.forEach(item => {
            html += `<a href="${item.url}" class="block p-2.5 bg-gray-50 hover:bg-[#2ba351] text-gray-700 hover:text-white border border-gray-100 rounded-xl text-center text-xs font-extrabold truncate transition-all duration-200 select-none">${item.title}</a>`;
        });

        html += `</div></div></div>`;
        viewport.innerHTML = html;
    };

    // 7. Filter Destinations by Sub-Area dynamically inside modal
    window.filterDestinationsBySubArea = function(cityName, subName, btn = null) {
        const destContainer = document.getElementById('destinationsSubList');
        if (!destContainer || !destinations || destinations.length === 0) return;

        document.querySelectorAll('.sub-area-btn').forEach(b => {
            b.className = "w-full text-left py-2.5 px-3 hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-bold transition-all cursor-pointer sub-area-btn";
        });
        
        if (btn) {
            btn.className = "w-full text-left py-2.5 px-3 bg-green-50 text-[#2ba351] rounded-xl text-xs font-bold transition-all cursor-pointer sub-area-btn";
        } else {
            document.querySelector('.sub-area-btn').className = "w-full text-left py-2.5 px-3 bg-green-50 text-[#2ba351] rounded-xl text-xs font-bold transition-all cursor-pointer sub-area-btn";
        }

        const filtered = destinations.filter(d => 
            d.city_area === cityName && (subName === '' || d.sub_area === subName)
        );

        destContainer.innerHTML = '';

        if (subName !== '') {
            const subRedirectUrl = `{{ route('web.project') }}?city_area=${encodeURIComponent(cityName)}&sub_area=${encodeURIComponent(subName)}`;
            
            const broadSearchBtn = document.createElement('a');
            broadSearchBtn.href = subRedirectUrl;
            broadSearchBtn.className = 'col-span-full block p-3 bg-blue-50 border border-blue-100 hover:bg-[#224194] text-[#224194] hover:text-white rounded-xl text-center text-xs font-extrabold transition-all duration-200 select-none mb-2';
            broadSearchBtn.innerHTML = `Search in All ${subName} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ms-1"></i>`;
            destContainer.appendChild(broadSearchBtn);
        }

        if (filtered.length === 0) {
            destContainer.innerHTML += '<div class="col-span-full text-center py-4 text-gray-400 text-xs font-semibold">No neighborhoods matching.</div>';
            return;
        }

        filtered.forEach(item => {
            const link = document.createElement('a');
            link.href = item.url;
            link.className = 'block p-2.5 bg-gray-50 hover:bg-[#2ba351] text-gray-700 hover:text-white border border-gray-100 rounded-xl text-center text-xs font-extrabold truncate transition-all duration-200 select-none';
            link.textContent = item.title;
            destContainer.appendChild(link);
        });
    };

    // 8. Real-time Search Box Filter Handler
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            if (!query) {
                const isSubView = !backBtn.classList.contains('hidden');
                if (isSubView) {
                    const activeCity = headerTitle.textContent.replace(' Sub-Areas', '');
                    renderSubAreasView(activeCity);
                } else {
                    renderAllCitiesView();
                }
                return;
            }

            const matchedDests = destinations.filter(d => 
                d.title.toLowerCase().includes(query) || 
                d.city_area.toLowerCase().includes(query) || 
                d.sub_area.toLowerCase().includes(query)
            );

            if (headerTitle) headerTitle.textContent = "Search Results";
            if (backBtn) backBtn.classList.remove('hidden');

            renderSearchedDestinations(matchedDests);
        });
    }

    // 9. Render searched destinations list on user typing
    function renderSearchedDestinations(items) {
        if (!viewport) return;
        viewport.innerHTML = '';

        if (items.length === 0) {
            viewport.innerHTML = '<div class="text-center py-8 text-gray-400 text-xs font-semibold">No matching areas found.</div>';
            return;
        }

        let html = `<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">`;
        items.forEach(item => {
            html += `<a href="${item.url}" class="block p-3 bg-gray-50 hover:bg-[#2ba351] text-gray-700 hover:text-white border border-gray-100 rounded-xl text-center text-xs font-extrabold truncate transition-all duration-200 select-none">
                        <span>${item.title}</span>
                        <span class="block text-[9px] opacity-60 font-semibold uppercase mt-0.5">${item.city_area} • ${item.sub_area}</span>
                     </a>`;
        });
        html += `</div>`;
        viewport.innerHTML = html;
    }

    // Keyboard support: Escape key closes the modal
    document.addEventListener('keydown', function(e) {
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeLocationModal();
            }
        }
    });
});
</script>