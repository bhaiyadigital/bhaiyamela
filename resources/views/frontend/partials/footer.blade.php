<!-- FOOTER SECTION -->
<footer class="bg-[#050a18] text-white pt-20 pb-8 font-manrope">
    <div class="container mx-auto">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-stretch border-b border-[#3D7B59]">
            <!-- Column 1: Logo & Social -->
            <div class="px-4 pb-16 lg:pb-20">
                <!-- Mobile: Row containing Logo & Socials | Desktop: Vertical Stack -->
                <div
                    class="flex flex-row md:flex-col items-center md:items-start justify-between md:justify-start gap-4 md:gap-0 mb-6">

                    <!-- Logo Container -->
                    <div class="mb-0 md:mb-6">
                        @if($setting && $setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}"
                                class="w-[80px]" />
                        @else
                            <span class="text-xl md:text-2xl font-bold text-white">
                                {{ $setting->site_name ?? 'Bhaiya Group' }}
                            </span>
                        @endif
                    </div>

                    <!-- Social Icons (Only Visible on Mobile inside the row) -->
                    <div class="flex md:hidden items-center gap-3">
                        @foreach ($socials as $social)
                            <a href="{{ $social->url }}" target="_blank" aria-label="Visit our social media profile"
                                class="w-8 h-8 rounded-full bg-[#4D6356] flex items-center justify-center hover:bg-[#2c4294] transition shadow-sm">
                                <i class="{{ $social->title }} text-base text-white"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Site Slogan -->
                <p class="text-white/60 text-base leading-relaxed mb-8 max-w-[280px]">
                    {{$setting->site_slogan ?? ''}}
                </p>

                <!-- Social Icons (Only Visible on Desktop at the bottom) -->
                <div class="hidden md:flex items-center gap-3">
                    @foreach ($socials as $social)
                        <a href="{{ $social->url }}" target="_blank" aria-label="Visit our social media profile"
                            class="w-8 h-8 rounded-full bg-[#4D6356] flex items-center justify-center hover:bg-[#2c4294] transition shadow-sm">
                            <i class="{{ $social->title }} text-base text-white"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div
                class="border-t lg:border-t-0 lg:border-l border-[#3D7B59] px-6 lg:px-12 flex flex-col items-center text-center pb-16 lg:pb-20 pt-12 lg:pt-0">
                <h3 class="text-xl font-bold uppercase tracking-[0.2em] mb-4">
                    Subscribe
                </h3>
                <p class="text-white/60 text-base mb-8 max-w-[220px]">
                    Stay updated with our latest projects and news.
                </p>

                <form action="{{ route('web.subscription') }}" id="subscribe-id" method="POST"
                    class="w-full max-w-[280px] space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="Email Address" required
                        class="w-full bg-transparent border border-white/20 rounded-full px-6 py-2.5 text-base focus:outline-none focus:border-white/50 text-center placeholder:text-white/30" />

                    <button type="submit"
                        class="w-full bg-[#2c4294] hover:bg-blue-800 text-white py-3 rounded-full font-bold text-base uppercase tracking-widest transition cursor-pointer">
                        Subscribe
                    </button>

                    @if (session('success'))
                        <p class="text-green-400 text-xs mt-2">{{ session('success') }}</p>
                    @endif
                    @error('email')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </form>

                @if (session('success') || $errors->has('email'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const form = document.getElementById('subscribe-id');
                            if (form) {
                                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    </script>
                @endif
            </div>

            <!-- Column 3: MENU -->
            <div class="border-t lg:border-t-0 lg:border-l border-[#3D7B59] px-6 lg:pl-20 pb-16 lg:pb-20 pt-12 lg:pt-0">
                <h3 class="text-xl font-bold uppercase tracking-[0.2em] mb-8">
                    Menu
                </h3>
                <ul class="space-y-4 text-base text-white/60">
                    <li>
                        <a href="{{ route('web.project') }}" class="hover:text-white transition"
                            aria-label="all projects">
                            All Projects
                        </a>
                    </li>

                    @foreach ($destination->take(4) as $dest)
                        <li>
                            <a href="{{ route('web.project', ['dest' => $dest->id]) }}" class="hover:text-white transition"
                                aria-label="destination">
                                {{ $dest->title }}
                            </a>
                        </li>
                    @endforeach


                    <li>
                        <a href="{{ route('requirements.create') }}" class="hover:text-white transition"
                            aria-label="post-requirement">
                            Post Your Requirements
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: INFORMATION -->
            <div class="border-t lg:border-t-0 px-6 lg:pl-10 pb-16 lg:pb-20 pt-12 lg:pt-0">
                <h3 class="text-xl font-bold uppercase tracking-[0.2em] mb-8">
                    Information
                </h3>
                <ul class="space-y-4 text-base text-white/60">
                    @foreach ($pages as $page)
                        <li>
                            <a href="{{ url('/page/' . $page->slug) }}" class="hover:text-white transition"
                                aria-label="pages">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('faq.index') }}" class="hover:text-white transition" aria-label="faq"> Faq

                        </a>

                    </li>
                    <li>

                        <a href="{{ route('loans.index') }}" class="hover:text-white transition" aria-label="faq"> Loan
                            Partners

                        </a>
                    </li>
                    <li>

                        <a href="{{ route('area-guides.index') }}" class="hover:text-white transition"
                            aria-label="area-guide">Area Guides

                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 text-center px-6">
            <p class="text-white/60 text-base tracking-widest">
                {!! $setting->footer_credit ?? '' !!}
            </p>
        </div>
    </div>
</footer>