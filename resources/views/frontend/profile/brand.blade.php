<section class="event-card w-full bg-black py-12 sm:py-16 md:py-20">
      <div class="container  mx-auto" >
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-16 items-center">
        <!-- Left Column - Heading with Image -->
        <div class="flex flex-col">
          <h2 class="text-xl md:text-xl lg:text-5xl font-medium text-[#ece2d6] leading-tight">
            Brand of
          </h2>
          <div class="flex items-center gap-4 mt-2">
            <h2 class="text-xl md:text-xl lg:text-5xl font-medium text-[#ece2d6]">
              Bhaiya Group
            </h2>

          </div>
        </div>

        <!-- Right Column - Description Text -->
        <div>
          <p class="text-gray-500 text-[#ece2d6] md:text-lg leading-relaxed">
            Lorem ipsum dolor sit amet consectetur. Aenean sollicitudin neque
            non nisi diam facilisis. Nunc enim tortor diam facilisi auctor
            purus Lorem ipsum dolor sit amet consectetur.
          </p>
        </div>
      </div>
    </div>
      <div class="container  mx-auto mt-12 text-center text-black">



        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
            @foreach ($brands as $brand)

            <div class="border bg-white border-gray-800 rounded-xl p-3 sm:p-4 flex items-center justify-center">
              <img src="{{ asset($brand->img_path ?? 'backend/assets/img/brand.webp') }}" alt="{{ $brand->title }}"
              class="h-16 sm:h-20 md:h-24 lg:h-28 w-auto opacity-80">
            </div>
            @endforeach

      </div>
    </div>
    </section>

