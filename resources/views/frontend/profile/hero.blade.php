@if($about )
        <section class="relative w-full overflow-hidden">
            <div class="relative h-[60vh] md:h-[80vh] lg:h-[100vh] flex items-center justify-center px-4 md:px-8 animate-shrink-header bg-cover bg-center"
                style="background: url('{{ asset($about->img_path) }}') no-repeat center center; background-size: cover;">

                <div class="absolute right-0 bottom-0 lg:bottom-0 lg:top-auto
                    bg-white text-black
                    rounded-tl-[35px]
                    w-full max-w-[300px] sm:max-w-[400px] md:max-w-[500px] lg:max-w-[600px]
                    px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12
                    py-4 sm:py-6
                    flex flex-col justify-center items-center text-center shadow-2xl"
                >
                    <h1 class="text-xl sm:text-xl md:text-xl lg:text-5xl font-semibold animated-font">
                        {{ $about->title }}
                    </h1>

                    <p class="text-xs sm:text-base md:text-base lg:text-lg mt-2 w-full text-gray-500">
                        <span class="text-gray-400">Home / </span>{{ $about->short }}
                    </p>
                </div>
            </div>
        </section>
    @endif
    