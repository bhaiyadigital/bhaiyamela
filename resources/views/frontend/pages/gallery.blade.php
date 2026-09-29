@extends('layouts.front')

@section('content')
    <!-- GALLERY SECTION -->
    <section class="bg-white py-16 md:py-24">
        <div class="container mx-auto px-6 md:px-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-16">
                <!-- Left Heading Area -->
                <div class="max-w-xl">
                    <h2 class="text-xl md:text-4xl lg:text-[32px] font-semibold text-gray-900 leading-tight">
                        A Glimpse of Paradise
                    </h2>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($galleryImages as $image)
                    <!-- Image Card - Wrap with <a> for Lightbox -->
                    <a href="{{ asset($image->img_path) }}"  aria-label="View gallery image"
                        class="glightbox relative overflow-hidden group cursor-pointer aspect-square lg:aspect-[4/3]">
                        <img src="{{ asset($image->img_path) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Gallery Image" />

                        <!-- Hover Overlay (Optional design) -->
                        <div
                            class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <i class="fa-solid fa-expand text-white text-xl"></i>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-400">No images found in gallery.</p>
                @endforelse
            </div>
            <div class="mt-16 flex justify-center">
                {{ $galleryImages->links('frontend.partials.custom_pagination') }}
            </div>
        </div>
    </section>
@endsection
