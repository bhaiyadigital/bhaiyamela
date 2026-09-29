@extends('layouts.front')
@section('meta')
@include('frontend.partials.meta', ['pageKey' => 'faq'])
@endsection
@section('content')
<section style="border: 1px solid gainsboro;margin-bottom: 20px;">
    <div class="container mx-auto px-6 md:px-10  ">
        <!-- Image Overlap using Negative Margin -->
        <div class="flex   gap-2 text-base  px-4 py-2  border-gray-100">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                Home
            </a>

            <span class="text-gray-300">›</span>

            <a href="#" class="text-gray-600 hover:text-black">
                Faq
            </a>


        </div>
    </div>
</section>
<div class="container mx-auto py-12 px-4 md:px-8 max-w-4xl">

    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-xl md:text-xl font-black text-[#2ba351] uppercase tracking-wide">Frequently Asked Questions</h1>
        <div class="w-24 h-1 bg-[#2ba351] mx-auto mt-2"></div>
    </div>

    <!-- Dynamic Accordion List -->
    @if($faqs->isEmpty())
    <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl p-8 max-w-md mx-auto shadow-sm">
        <i class="fa-solid fa-circle-question text-gray-300 text-5xl mb-4"></i>
        <h2 class="font-bold text-gray-700 text-base">No Questions Yet</h2>
        <p class="text-xs text-gray-400 mt-2">We are currently updating our FAQ section. Please check back later.</p>
    </div>
    @else
    <div class="space-y-4 select-none">
        @foreach($faqs as $index => $faq)
        <!-- Individual FAQ Item Card -->
        <div class="faq-item bg-white border border-gray-100 rounded-2xl shadow-[0_5px_15px_rgba(0,0,0,0.01)] overflow-hidden transition-all duration-300">

            <!-- Question Button Trigger -->
            <button type="button"
                class="faq-btn w-full flex justify-between items-center p-5 text-left text-gray-800 hover:text-[#2ba351] font-bold text-base md:text-base focus:outline-none transition-colors gap-4 cursor-pointer"
                data-target="faq-answer-{{ $faq->id }}">
                <span>{{ $faq->title }}</span>
                <!-- Rotating FontAwesome Arrow Icon -->
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 flex-shrink-0"></i>
            </button>

            <!-- Collapsible Answer Panel (max-h-0 transitions to scrollHeight smoothly) -->
            <div id="faq-answer-{{ $faq->id }}" class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-gray-50/50">
                <div class="p-5 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-100 prose max-w-none">
                    {!! $faq->description !!}
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- ── Smooth Sliding Accordion JavaScript ── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqButtons = document.querySelectorAll('.faq-btn');

        faqButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const answerContainer = document.getElementById(targetId);
                const icon = this.querySelector('.fa-chevron-down');

                if (!answerContainer) return;

                // Close all other open accordions except the currently clicked one
                document.querySelectorAll('.faq-answer').forEach(el => {
                    if (el.id !== targetId) {
                        el.style.maxHeight = null;
                        const otherBtn = el.previousElementSibling;
                        if (otherBtn) {
                            otherBtn.querySelector('.fa-chevron-down')?.classList.remove('rotate-180');
                            otherBtn.classList.remove('text-[#2ba351]');
                        }
                    }
                });

                // Toggle current clicked accordion smoothly using scrollHeight
                if (answerContainer.style.maxHeight) {
                    answerContainer.style.maxHeight = null;
                    icon.classList.remove('rotate-180');
                    this.classList.remove('text-[#2ba351]');
                } else {
                    answerContainer.style.maxHeight = answerContainer.scrollHeight + "px"; // Expands to dynamic height
                    icon.classList.add('rotate-180');
                    this.classList.add('text-[#2ba351]');
                }
            });
        });
    });
</script>
@endsection