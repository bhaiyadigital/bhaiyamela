<section class="relative bg-[#1c1911] overflow-hidden">
    <div>
        <div class="grid grid-cols-1 lg:grid-cols-2 items-center">

            <div class="relative h-[260px] sm:h-[300px] md:h-[360px] lg:h-[500px]">
                {{-- ?-> operator bebohar korle data na thakle error ashbe na --}}
                <img
                    src="{{ asset($founder?->img_path) }}"
                    alt="{{ $founder?->name }}"
                    class="w-full h-full object-cover opacity-80"
                />

                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#050b1a]/60 to-[#050b1a]"></div>
            </div>

            <div class="text-white px-2 sm:px-3 md:px-0 lg:pl-16 mt-4 sm:mt-6 md:mt-8 lg:mt-0">

                <h2 class="text-xl text-[#ece2d6] sm:text-xl md:text-4xl lg:text-5xl font-bold mb-2">
                    {{ $founder?->title }}
                </h2>

                <p class="text-blue-400 text-base sm:text-base md:text-lg font-medium mb-3">
                    {{ $founder?->name }}
                </p>

                <p class="text-gray-300 text-xs sm:text-base md:text-base lg:text-lg leading-relaxed max-w-full lg:max-w-xl">
                    {!! $founder?->body !!}
                </p>
            </div>

        </div>
    </div>
</section>

