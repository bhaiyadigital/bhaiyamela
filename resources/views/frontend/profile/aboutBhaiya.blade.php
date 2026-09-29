<style> 
   .body li p{
        margin: 0 !important;
    }
</style>
<section class="w-full py-24 px-6 lg:px-16 bg-black">
    <div class="container  mx-auto"  >
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-16 items-center">
            <div class="flex flex-col">
                <h2 class="text-xl md:text-xl lg:text-5xl font-medium text-[#ece2d6] leading-tight">
                    About

                </h2>
                <div class="flex items-center gap-4 mt-2">
                    <h2 class="text-xl md:text-xl lg:text-5xl font-medium text-[#ece2d6]">
                       Bhaiya Group
                    </h2>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-[#ece2d6] md:text-lg leading-relaxed">
                    {{-- Uporer description-ti jodi backend theke ante chan tobe ekhane logic bishye nite paren --}}
                    Discover why Salt Bay is the premier choice for investors seeking sustainable growth and luxury living.
                </p>
            </div>
        </div>
    </div>

    <div class="container  mx-auto"  >
        <div class="grid mt-16 grid-cols-1 lg:grid-cols-2 gap-8">

            @if($aboutBhaiya)
            @php
                // Image gulo JSON string thakle decode kore array baniye nitem
                $images = json_decode($aboutBhaiya->img_paths) ?? [];
            @endphp

            <div class="lg:col-span-1">
                <div class="image-container relative bg-blue-100  overflow-hidden shadow-xl min-h-[400px]">
                    {{-- Main Display Image --}}
                    <img id="mainImage" src="{{ asset($images[0] ?? 'default.jpg') }}"
                         alt="{{ $aboutBhaiya->title }}" class="main-image fade-in w-full h-full object-cover" />

                    <!-- <div class="absolute bottom-6 right-4 flex gap-3 rounded-full px-4 py-3 shadow-lg bg-black/20 backdrop-blur-sm">
                        @foreach($images as $index => $img)
                        <div class="thumbnail {{ $index == 0 ? 'active border-blue-500' : 'border-gray-200' }} w-12 h-12 rounded-full overflow-hidden border-2 cursor-pointer transition-all hover:scale-110"
                             data-image="{{ asset($img) }}">
                            <img src="{{ asset($img) }}" alt="Thumbnail {{ $index }}" class="w-full h-full object-cover" />
                        </div>
                        @endforeach
                    </div> -->
                </div>
            </div>

            <div class="lg:col-span-1 flex flex-col justify-between rounded-3xl px-4">
                <div>
                 
                    <div class="body text-gray-400 text-lg mb-8 leading-relaxed">
                        {!! $aboutBhaiya->body !!}
                    </div>
                    
                </div>
            </div>
             
            @endif

        </div>


       



        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center mt-20 ">

          <div class="md:border-r border-gray-300">
              <h3 class="text-5xl font-bold text-[#ece2d6]">3,000+</h3>
              <p class="text-gray-500 text-xl mt-2">Employees  </p>
          </div>

          <div class="md:border-r border-gray-300">
              <h3 class="text-5xl font-bold text-[#ece2d6]">  1972</h3>
              <p class="text-gray-500  text-xl mt-2">Legacy & Trust  </p>
          </div>

          <div class="md:border-r border-gray-300">
              <h3 class="text-5xl font-bold text-[#ece2d6]">18 </h3>
              <p class="text-gray-500  text-xl mt-2">Concerns Across Industries </p>
          </div>

          <div>
              <h3 class="text-5xl font-bold text-[#ece2d6]">  30+</h3>
              <p class="text-gray-500 text-xl mt-2">Luxury Hospitality   </p>
          </div>

        </div>
    </div>
</section>
