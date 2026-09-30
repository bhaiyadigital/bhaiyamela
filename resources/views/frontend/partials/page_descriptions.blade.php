<style>
    table {
        width: 100%;
    }

    table th,
    table td {
        border: 1px solid black !important;
        padding: 3px 5px;
    }

    ul {
        list-style: disc;
        padding-left: 20px;
    }

    ol {
        list-style: decimal;
        padding-left: 20px;
    }

    .blog-content h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .blog-content h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .blog-content h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .blog-content h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .blog-content h5 {
        font-size: 18px;
        font-weight: 600;
    }

    .blog-content h6 {
        font-size: 16px;
        font-weight: 600;
    }

    .blog-content p {
        margin-bottom: 16px;
        line-height: 1.8;
    }

    .blog-content ul {
        list-style: disc;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content ol {
        list-style: decimal;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content li {
        margin-bottom: 0;
    }

    .blog-content strong {
        font-weight: 700;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 16px 0;
    }

    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5 {
        margin: 0;
        margin-top: 10px;
    }

    p {
        margin: 0;
    }

    .blog-content * {
        font-family: "Trebuchet MS", "sans-serif" !important;
        color: black;
    }

    .blog-content h2,
    .blog-content h3 {
        font-weight: 400;
        font-size: 20px;
        line-height: 26px;
        margin-bottom: 5px;
    }

    .blog-content p {
        font-size: 15px;
        line-height: 23px;
        text-align: justify;
        color: #333333fa;
    }

    .hr {
        height: 1px;
        background: gainsboro;
        margin-top: 30px;
    }
</style>

@php
    $item = $model ?? ($page ?? ($project ?? null));

    $previewMode = request()->query('preview') === '1';

    $descActive = $item ? ((string) $item->description_status === '1') : false;
    $desc1Active = $item ? ((string) $item->description_1_status === '1') : false;
    $desc2Active = $item ? ((string) $item->description_2_status === '1') : false;

    $hasRenderableContent = false;
    if ($item) {
        if (($descActive || $previewMode) && !empty($item->description))
            $hasRenderableContent = true;
        if (($desc1Active || $previewMode) && !empty($item->description_1))
            $hasRenderableContent = true;
        if (($desc2Active || $previewMode) && !empty($item->description_2))
            $hasRenderableContent = true;
    }
@endphp

@if($item && $hasRenderableContent)
    <!-- Single Shared Card Container for all active accordions -->
    <div
        class="container px-4 bg-white p-4 border border-gray-100 shadow-sm flex flex-col gap-3 select-none relative w-full mb-8">

        <!-- Box 1 / Description 1 Accordion -->
        @if(($descActive || $previewMode) && !empty($item->description))
            <div class="overflow-hidden transition-all duration-300 relative">
                @if(!$descActive)
                    <span
                        class="absolute top-4 right-14 bg-red-50 text-red-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-red-200 z-10">PREVIEW</span>
                @endif

                <button type="button"
                    class="desc-faq-btn w-full flex justify-between items-center p-5 text-left text-gray-800 hover:text-[#2ba351] font-bold text-base md:text-base focus:outline-none transition-colors gap-4 cursor-pointer"
                    data-target="desc-answer-1">
                    <span>{{ $item->description_title }}</span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 flex-shrink-0"></i>
                </button>

                <!-- style="max-height: 0px;" added as fallback to prevent any styling overrides -->
                <div id="desc-answer-1" class="desc-faq-answer overflow-hidden transition-all duration-300 ease-in-out "
                    style="max-height: 0px;">
                    <div class="p-5 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-100 blog-content">
                        {!! $item->description !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Box 2 / Description 2 Accordion -->
        @if(($desc1Active || $previewMode) && !empty($item->description_1))
            <div class=" overflow-hidden transition-all duration-300 relative">
                @if(!$desc1Active)
                    <span
                        class="absolute top-4 right-14 bg-red-50 text-red-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-red-200 z-10">PREVIEW</span>
                @endif

                <button type="button"
                    class="desc-faq-btn w-full flex justify-between items-center p-5 text-left text-gray-800 hover:text-[#2ba351] font-bold text-base md:text-base focus:outline-none transition-colors gap-4 cursor-pointer"
                    data-target="desc-answer-2">
                    <span>{{ $item->description_1_title }}</span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 flex-shrink-0"></i>
                </button>

                <div id="desc-answer-2" class="desc-faq-answer overflow-hidden transition-all duration-300 ease-in-out "
                    style="max-height: 0px;">
                    <div class="p-5 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-100 blog-content">
                        {!! $item->description_1 !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Box 3 / Description 3 Accordion -->
        @if(($desc2Active || $previewMode) && !empty($item->description_2))
            <div class=" overflow-hidden transition-all duration-300 relative">
                @if(!$desc2Active)
                    <span
                        class="absolute top-4 right-14 bg-red-50 text-red-500 text-[9px] font-bold px-1.5 py-0.5 rounded border border-red-200 z-10">PREVIEW</span>
                @endif

                <button type="button"
                    class="desc-faq-btn w-full flex justify-between items-center p-5 text-left text-gray-800 hover:text-[#2ba351] font-bold text-base md:text-base focus:outline-none transition-colors gap-4 cursor-pointer"
                    data-target="desc-answer-3">
                    <span>{{ $item->description_2_title }}</span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 flex-shrink-0"></i>
                </button>

                <div id="desc-answer-3" class="desc-faq-answer overflow-hidden transition-all duration-300 ease-in-out "
                    style="max-height: 0px;">
                    <div class="p-5 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-100 blog-content">
                        {!! $item->description_2 !!}
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const descButtons = document.querySelectorAll('.desc-faq-btn');

            descButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const answer = document.getElementById(targetId);
                    const icon = this.querySelector('.fa-chevron-down');

                    if (!answer) return;

                    document.querySelectorAll('.desc-faq-answer').forEach(el => {
                        if (el.id !== targetId) {
                            el.style.maxHeight = "0px"; // Force close
                            const otherBtn = el.previousElementSibling;
                            if (otherBtn) {
                                otherBtn.querySelector('.fa-chevron-down')?.classList.remove('rotate-180');
                                otherBtn.classList.remove('text-[#2ba351]');
                            }
                        }
                    });

                    if (answer.style.maxHeight && answer.style.maxHeight !== "0px") {
                        answer.style.maxHeight = "0px"; // Close current accordion
                        icon.classList.remove('rotate-180');
                        this.classList.remove('text-[#2ba351]');
                    } else {
                        answer.style.maxHeight = answer.scrollHeight + "px"; // Open current accordion
                        icon.classList.add('rotate-180');
                        this.classList.add('text-[#2ba351]');
                    }
                });
            });
        });
    </script>
@endif