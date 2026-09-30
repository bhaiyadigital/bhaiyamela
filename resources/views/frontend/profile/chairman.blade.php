 <section class="event-card bg-black">
      <div class=" ">

        <div class="grid grid-cols-1 lg:grid-cols-2   items-middle  ">


          <div class="text-white px-2 sm:px-3 md:px-0 lg:pl-16 mt-4 sm:mt-6 md:mt-8 lg:mt-0">

            <h2 class="text-xl pt-4 text-[#ece2d6] sm:text-xl md:text-4xl lg:text-5xl font-bold mb-2 sm:mb-3 md:mb-4">
             {{ $chairman?->title ?? 'Voice Of Chairman' }}
            </h2>

          
            <div  class="text-gray-300  text-xs sm:text-base md:text-base lg:text-lg leading-relaxed  pr-4">
              {!! $chairman?->body !!}
</div>
          </div>
           <div>
            <img
              src="{{ asset($chairman->img_path ?? 'backend/assets/img/chairman.webp') }}"
              alt="{{ $chairman->name ?? 'Chairman' }}"
              class="w-full   shadow-lg object-cover"
            />
          </div>
        </div>

      </div>
    </section>

