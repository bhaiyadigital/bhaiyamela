<section class="event-card w-full  bg-[#1c1911]  relative  ">


      <div class="relative z-10  ">

        <div class="grid grid-cols-1 lg:grid-cols-2  text-white">
        <div class="flex justify-center lg:justify-end">
            <img
              src="{{ asset($ceo->img_path ?? 'backend/assets/img/ceo.webp') }}"
              alt="{{ $ceo->name ?? 'CEO' }}"
              class="w-full "
            />
          </div>
          <!-- TEXT -->
          <div class="text-gray-300 leading-relaxed mb-6 lg:mb-0">

             <div class="text-white px-2 sm:px-3 md:px-0 lg:pl-16 mt-4 sm:mt-6 md:mt-8 lg:mt-0">

            <h2 class="text-xl mt-6 text-[#ece2d6] sm:text-xl md:text-4xl lg:text-5xl font-bold mb-2 sm:mb-3 md:mb-4">
              {{ $ceo->title ?? 'test' }}
            </h2>

            

            <div class="  text-gray-300 text-xs sm:text-base md:text-base lg:text-lg leading-relaxed  ">
              {!! $ceo->body !!}
</div>
          </div>

          </div>

        </div>

      </div>
    </section>
