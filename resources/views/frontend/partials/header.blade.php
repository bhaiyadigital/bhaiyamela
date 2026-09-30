<header class="bg-white sticky top-0 z-50">
    <div class="container mx-auto px-4 h-24 flex items-center justify-between">
        <!-- Logo Section -->
        <div class="flex-shrink-0">
            <a href="/" class="flex flex-col items-center">
                <img src="{{ asset('storage/' . $setting->logo ?? '') }}" alt="logo" class="w-[80px]" />
            </a>
        </div>

        <!-- Navigation Links (Desktop) -->
        <nav class="hidden lg:flex items-center gap-5 font-semibold text-lg">
            <!-- Home link matches your color now via .nav-link CSS -->
            <a href="{{ route('web.home') }}" class="nav-link active-link" aria-label="Home">Home</a>
            <a href="{{ route('web.project') }}" class="nav-link active-link" aria-label="Properties">Properties</a>
            <div class="relative group cursor-pointer">
                <button class="nav-link flex items-center gap-1 cursor-pointer" aria-haspopup="true"
                    aria-expanded="false">
                    Category
                    <i class="fa-solid fa-chevron-down text-[10px] mt-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div
                    class="absolute hidden group-hover:block top-full left-0 bg-white shadow-xl rounded-lg p-4 w-52 border border-gray-100 z-50">
                    @forelse($categories as $category)
                        <a href="{{ route('web.project.category', $category->slug) }}"
                            class="block py-2 text-base text-gray-600 hover:text-[#2c4294] transition-colors duration-200">
                            {{ $category->title }}
                        </a>
                    @empty
                        <span class="block py-2 text-xs text-gray-400">No Category Available</span>
                    @endforelse
                </div>
            </div>
            <!-- <a href="{{ route('developers.index') }}" class="nav-link" aria-label="Developers">Concerns</a> -->
            <a href="{{ route('web.blog') }}" class="nav-link" aria-label="Blogs">Blog</a>
            {{-- @auth
            @if(auth()->user()->isCompany() || auth()->user()->isAdmin())
            <!-- If logged in as Admin or Developer Company -->
            <a href="{{route('home')}}" class="nav-link" aria-label="Admin Dashboard">Dashboard</a>
            @else
            <!-- If logged in as Normal Individual User -->
            <a href="{{ route('user.dashboard') }}" class="nav-link" aria-label="User Profile">Profile</a>
            @endif
            @else
            <!-- If Guest (Not Logged In) -->
            <a href="{{ route('company.login') }}" class="nav-link" aria-label="Company Login">Login</a>
            @endauth --}}
        </nav>

        <!-- Right Side Button -->
        <button id="menu-toggle" class="lg:hidden text-gray-800 text-2xl" aria-label="Open Menu">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </div>

    <!-- MOBILE DRAWER -->
    <div id="mobile-drawer" class="fixed inset-0 z-[60] lg:hidden translate-x-full transition-transform duration-300">
        <div id="drawer-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="absolute right-0 top-0 h-full w-[280px] bg-white p-8 shadow-2xl flex flex-col">
            <div class="flex justify-between items-center mb-10">
                <span class="font-bold text-xl text-brand-blue">Menu</span>
                <button id="close-drawer" class="text-gray-500 text-xl" aria-label="Close Menu">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex flex-col space-y-6">
                <a href="{{ route('web.home') }}" class="text-gray-900 font-semibold text-lg" aria-label="Home">Home</a>
                <a href="{{ route('web.project') }}"
                    class="text-gray-900 font-semibold text-lg border-b border-gray-50 pb-2"
                    aria-label="Developers">Properties</a>
                <!-- Mobile Destination Dropdown (Click to Expand) -->
                <div class="border-b border-gray-50 pb-2">
                    <!-- Click handler to toggle dropdown with smooth chevron rotation -->
                    <button type="button" onclick="toggleMobileDestDropdown()"
                        class="w-full flex justify-between items-center text-gray-900 font-semibold text-lg focus:outline-none cursor-pointer">
                        Category
                        <i id="mobileDestIcon"
                            class="fa-solid fa-chevron-down text-base transition-transform duration-300"></i>
                    </button>

                    <!-- Mobile Dropdown Menu Items (Hidden by default) -->
                    <div id="mobileDestMenu" class="hidden flex flex-col gap-2 pl-4 mt-3 select-none">
                        @forelse($categories as $category)
                            <a href="{{ route('web.project.category', $category->slug) }}"
                                class="block py-2 text-base text-gray-600 hover:text-[#2c4294] transition-colors duration-200">
                                {{ $category->title }}
                            </a>
                        @empty
                            <span class="block py-2 text-xs text-gray-400">No Category Available</span>
                        @endforelse
                    </div>
                </div>
                <!-- <a href="{{ route('developers.index') }}"
                    class="text-gray-900 font-semibold text-lg border-b border-gray-50 pb-2"
                    aria-label="Developers">Concerns</a> -->
                <a href="{{ route('web.blog') }}"
                    class="text-gray-900 font-semibold text-lg border-b border-gray-50 pb-2" aria-label="Blogs">Blog</a>
                {{-- @auth
                @if(auth()->user()->isCompany() || auth()->user()->isAdmin())
                <a href="{{route('home')}}" class="nav-link" aria-label="Admin Dashboard">Dashboard</a>
                @else
                <!-- If logged in as Normal Individual User -->
                <a href="{{ route('user.dashboard') }}" class="nav-link" aria-label="User Profile">Profile</a>
                @endif
                @else
                <!-- If Guest (Not Logged In) -->
                <a href="{{ route('company.login') }}" class="nav-link" aria-label="Company Login">Login</a>
                @endauth --}}


            </div>
        </div>
    </div>
</header>
<script>
    window.toggleMobileDestDropdown = function () {
        const menu = document.getElementById('mobileDestMenu');
        const icon = document.getElementById('mobileDestIcon');

        if (menu && icon) {
            menu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    };
</script>