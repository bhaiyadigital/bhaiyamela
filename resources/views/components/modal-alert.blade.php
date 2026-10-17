@props([
    'id' => 'alertModal',
    'type' => 'success', // 'success', 'error', 'info', 'warning'
    'title' => null,
    'message' => null,
    'sessionKey' => null,
    'buttonText' => 'ঠিক আছে',
    'autoClose' => 0, // milliseconds
    'scoped' => false, // if true, modal stays centered inside its parent container
])

@php
    $flashMessage = $sessionKey ? session($sessionKey) : null;
    $finalMessage = $flashMessage ?: $message;
    $shouldShow = !empty($finalMessage);

    $defaultTitles = [
        'success' => 'সফল হয়েছে!',
        'error' => 'ত্রুটি!',
        'warning' => 'সতর্কতা!',
        'info' => 'তথ্য',
    ];
    $finalTitle = $title ?: ($defaultTitles[$type] ?? 'নোটিফিকেশন');

    $colors = [
        'success' => [
            'iconBg' => 'bg-green-100',
            'iconColor' => 'text-green-500',
            'icon' => 'fa-solid fa-check',
        ],
        'error' => [
            'iconBg' => 'bg-red-100',
            'iconColor' => 'text-red-500',
            'icon' => 'fa-solid fa-xmark',
        ],
        'warning' => [
            'iconBg' => 'bg-amber-100',
            'iconColor' => 'text-amber-500',
            'icon' => 'fa-solid fa-triangle-exclamation',
        ],
        'info' => [
            'iconBg' => 'bg-blue-100',
            'iconColor' => 'text-blue-500',
            'icon' => 'fa-solid fa-circle-info',
        ],
    ];

    $style = $colors[$type] ?? $colors['success'];
    $positionClass = $scoped ? 'absolute inset-0 z-30 rounded-2xl bg-white/90 backdrop-blur-sm' : 'fixed inset-0 z-[9999] bg-black/50 backdrop-blur-sm';
@endphp

<!-- Common Alert Modal Element -->
<div id="{{ $id }}"
    class="{{ $positionClass }} flex items-center justify-center p-4 transition-all duration-300 {{ $shouldShow ? 'opacity-100' : 'opacity-0 pointer-events-none hidden' }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title">
    
    <!-- Modal Card (Styled matching details.blade.php with enhancements) -->
    <div id="{{ $id }}-content" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center mx-4 transform transition-all duration-300 {{ $shouldShow ? 'scale-100 opacity-100' : 'scale-95 opacity-0' }}">
        
        <!-- Close Button (Top-Right) -->
        <button type="button" 
            onclick="closeAlertModal('{{ $id }}')" 
            class="absolute top-3.5 right-3.5 w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer"
            aria-label="Close">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>

        <!-- Icon Circle -->
        <div id="{{ $id }}-icon-bg" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 {{ $style['iconBg'] }} shadow-inner">
            <i id="{{ $id }}-icon" class="{{ $style['icon'] }} text-4xl {{ $style['iconColor'] }} animate-bounce"></i>
        </div>

        <!-- Title -->
        <h2 id="{{ $id }}-title" class="text-2xl font-bold text-gray-800 mb-2">
            {{ $finalTitle }}
        </h2>

        <!-- Message Body -->
        <p id="{{ $id }}-message" class="text-gray-600 mb-6 font-medium text-base leading-relaxed whitespace-pre-line">
            {{ $finalMessage }}
        </p>

        <!-- Action Button -->
        <button type="button" 
            onclick="closeAlertModal('{{ $id }}')"
            class="cursor-pointer w-full bg-[#2c4294] text-white rounded-xl py-3.5 font-bold text-base hover:bg-[#1a285a] shadow-md hover:shadow-lg transition-all active:scale-95">
            {{ $buttonText }}
        </button>
    </div>
</div>

<script>
    if (typeof window.closeAlertModal === 'undefined') {
        window.closeAlertModal = function(modalId = '{{ $id }}') {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const content = document.getElementById(modalId + '-content') || modal.firstElementChild;
            
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        // Global function to trigger modal dynamically from JavaScript (AJAX / Fetch)
        window.showAlertModal = function(message, type = 'success', title = null, modalId = '{{ $id }}') {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            const content = document.getElementById(modalId + '-content');
            const iconBg = document.getElementById(modalId + '-icon-bg');
            const icon = document.getElementById(modalId + '-icon');
            const titleEl = document.getElementById(modalId + '-title');
            const msgEl = document.getElementById(modalId + '-message');

            if (msgEl) msgEl.textContent = message;

            const config = {
                success: {
                    title: 'সফল হয়েছে!',
                    bg: 'bg-green-100',
                    color: 'text-green-500',
                    icon: 'fa-solid fa-check'
                },
                error: {
                    title: 'ত্রুটি!',
                    bg: 'bg-red-100',
                    color: 'text-red-500',
                    icon: 'fa-solid fa-xmark'
                },
                warning: {
                    title: 'সতর্কতা!',
                    bg: 'bg-amber-100',
                    color: 'text-amber-500',
                    icon: 'fa-solid fa-triangle-exclamation'
                },
                info: {
                    title: 'তথ্য',
                    bg: 'bg-blue-100',
                    color: 'text-blue-500',
                    icon: 'fa-solid fa-circle-info'
                }
            };

            const t = config[type] || config.success;

            if (titleEl) titleEl.textContent = title || t.title;
            if (iconBg) iconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 ' + t.bg + ' shadow-inner';
            if (icon) icon.className = t.icon + ' text-4xl ' + t.color + ' animate-bounce';

            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            setTimeout(() => {
                if (content) {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        };

        // Close on backdrop click (for fixed full-screen mode)
        document.addEventListener('click', function(e) {
            if (e.target && e.target.getAttribute('role') === 'dialog' && e.target.classList.contains('fixed')) {
                closeAlertModal(e.target.id);
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('[role="dialog"]:not(.hidden):not(.opacity-0)');
                openModals.forEach(modal => closeAlertModal(modal.id));
            }
        });
    }

    @if($shouldShow)
    // Auto-scroll into view if needed
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $id }}');
        if (modal) {
            modal.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    @if($autoClose > 0)
        setTimeout(() => {
            closeAlertModal('{{ $id }}');
        }, {{ $autoClose }});
    @endif
    @endif
</script>
