<!-- FOOTER SECTION -->
<footer class="bg-[#050a18] text-white pt-10 md:pt-20 pb-8">
    <div class="container mx-auto">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-stretch border-b border-[#2c4294]">
            <!-- Column 1: Logo & Social -->
            <div class="px-4 pb-10 lg:pb-20">
                <!-- Mobile: Row containing Logo & Socials | Desktop: Vertical Stack -->
                <div
                    class="flex flex-row md:flex-col items-center md:items-start justify-between md:justify-start gap-4 md:gap-0 mb-6">

                    <!-- Logo Container -->
                    <div class="mb-0 md:mb-6">
                        @if($setting && $setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}" width="80" height="80"
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
                                class="w-8 h-8 rounded-full bg-[#2c4294] flex items-center justify-center hover:bg-[#2c4294] transition shadow-sm">
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
                            class="w-8 h-8 rounded-full bg-[#2c4294] flex items-center justify-center hover:bg-[#2c4294] transition shadow-sm">
                            <i class="{{ $social->title }} text-base text-white"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div
                class="border-t lg:border-t-0 lg:border-l border-[#2c4294] px-6 lg:px-12 flex flex-col items-center text-center pb-10 lg:pb-20 pt-10 lg:pt-0">
                <h2 class="text-xl font-bold uppercase mb-4">
                    সাবস্ক্রাইব
                </h2>
                <p class="text-white/60 text-base mb-8 max-w-[220px]">
                    আমাদের নতুন প্রকল্প এবং খবরের সাথে আপডেট থাকুন।
                </p>

                <form action="{{ route('web.subscription') }}" id="subscribe-id" method="POST"
                    class="w-full max-w-[280px] space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="ইমেইল এড্রেস" required
                        class="w-full bg-transparent border border-white/20 rounded-full px-6 py-2.5 text-base focus:outline-none focus:border-white/50 text-center placeholder:text-white/30" />

                    <button type="submit"
                        class="w-full bg-[#2c4294] hover:bg-blue-800 text-white py-3 rounded-full font-bold text-base uppercase tracking-widest transition cursor-pointer">
                        সাবস্ক্রাইব
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
            <div class="border-t lg:border-t-0 lg:border-l border-[#2c4294] px-6 lg:pl-20 pb-10 lg:pb-20 pt-10 lg:pt-0">
                <h2 class="text-xl font-bold uppercase  mb-8">
                    মেনু
                </h2>
                <ul class="space-y-4 text-base text-white/60">
                    <li>
                        <a href="{{ route('web.project') }}" class="hover:text-white transition"
                            aria-label="all projects">
                            সব প্রকল্প
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
                            আপনার চাহিদা জানান
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: RECENT PROJECTS -->
            <div class="border-t lg:border-t-0 border-[#2c4294] px-6 lg:pl-10 pb-10 lg:pb-20 pt-10 lg:pt-0">
                <h2 class="text-xl font-bold uppercase  mb-8">
                    সাম্প্রতিক প্রকল্প
                </h2>
                <ul class="space-y-4 text-base text-white/60">
                    @php
                        $footerProjects = \App\Models\Content::where('module', 'project')
                            ->active()
                            ->approved()
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    @foreach ($footerProjects as $fProject)
                        <li>
                            <a href="{{ route('project.details', $fProject->slug) }}"
                                class="hover:text-white transition line-clamp-1" aria-label="{{ $fProject->title }}">
                                {{ $fProject->title }}
                            </a>
                        </li>
                    @endforeach
                    @if($footerProjects->isEmpty())
                        <li>কোন প্রকল্প পাওয়া যায়নি।</li>
                    @endif
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