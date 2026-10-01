<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ config('app.name') }} - @yield('title', 'Bhaiya Mela')</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <link rel="icon" type="image/webp" href="{{ asset('assets/images/fav.webp') }}">

    <link rel="preload" href="{{asset('/')}}backend/css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{asset('/')}}backend/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body" style="border-bottom: 1px solid #e9ecef;">
            <div class="container-fluid">

                {{-- Left side --}}
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list fs-5"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="/" class="nav-link text-muted small" target="_blank">
                            <i class="bi bi-globe2 me-1"></i> View Site
                        </a>
                    </li>
                </ul>

                {{-- Right side --}}
                <ul class="navbar-nav ms-auto align-items-center gap-1">

                    @auth
                        @if(auth()->user()->hasPermission('roles.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link text-muted small {{ request()->routeIs('admin.users.*') ? 'fw-semibold text-dark' : '' }}">
                                    <i class="bi bi-people me-1"></i>
                                    <span class="d-none d-md-inline">Users</span>
                                </a>
                            </li>
                        @endif
                    @endauth

                    {{-- Divider --}}
                    <li class="nav-item d-none d-md-block">
                        <span
                            style="width:1px;height:20px;background:#dee2e6;display:inline-block;vertical-align:middle;"></span>
                    </li>

                    {{-- User dropdown --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-0" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">

                                {{-- Avatar --}}
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                    style="width:30px;height:30px;font-size:12px;flex-shrink:0;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>

                                <div class="d-none d-md-block lh-sm">
                                    <div class="fw-semibold text-dark" style="font-size:0.825rem;line-height:1.2;">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem;">
                                        {{ auth()->user()->roles->first()?->name ?? 'No Role' }}
                                    </div>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1"
                                style="min-width:200px;border-radius:10px;">

                                {{-- User info header --}}
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-semibold text-dark" style="font-size:0.85rem;">{{ auth()->user()->name }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ auth()->user()->email }}</div>
                                </li>


                                @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                                    {{-- Edit profile --}}
                                    <li>
                                        <a class="dropdown-item small py-2"
                                            href="{{ route('admin.users.edit', auth()->id()) }}">
                                            <i class="bi bi-person-gear me-2 text-muted"></i> Edit Profile
                                        </a>
                                    </li>
                                @endif

                                {{-- Users list — if has permission --}}
                                @if(auth()->user()->hasPermission('roles.view'))
                                    <li>
                                        <a class="dropdown-item small py-2" href="{{ route('admin.users.index') }}">
                                            <i class="bi bi-people me-2 text-muted"></i> Manage Users
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a class="dropdown-item small py-2" href="{{ route('admin.password.edit') }}">
                                        <i class="bi bi-shield-fill me-2 text-muted"></i> Update password
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>

                                {{-- Logout --}}
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item small py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                </ul>

            </div>
        </nav>
        <!--end::Header-->
        <style>
            .active {
                background: #484e53 !important
            }
        </style>
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="/" class="brand-link">
                    <!--begin::Brand Image-->

                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">Bhaiya Mela</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                        aria-label="Main navigation" data-accordion="false" id="navigation">

                        {{-- Search --}}
                        <li id="search">
                            <input type="text" id="menuSearch" class="form-control" placeholder="Search menu...">
                        </li>

                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="/home" class="nav-link {{ request()->is('*/home') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2 text-info"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li style="margin:0;border-bottom:1px solid var(--bs-border-color)"></li>

                        {{-- Content modules --}}
                        @foreach($modules as $field => $data)

                            @php
                                $icon = $data['module_name']['icon'] ?? 'bi bi-circle';

                                $isActive = (request()->routeIs('admin.contents.create') && request()->route('module') == $field)
                                    || (request()->routeIs('admin.contents.index') && request()->route('module') == $field);

                                $separatorBefore = ['pages', 'social', 'settings'];

                                $canView = auth()->user()->hasPermission("{$field}.view");
                                $canCreate = auth()->user()->hasPermission("{$field}.create");
                            @endphp

                            {{-- Only show module if user can at least view it --}}
                            @if($canView)

                                @if(in_array($field, $separatorBefore))
                                    <li style="margin:0;border-bottom:1px solid var(--bs-border-color)"></li>
                                @endif

                                <li class="nav-item nav-items {{ $isActive ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActive ? 'active' : '' }}">
                                        <i class="nav-icon {{ $icon }} text-info"></i>
                                        <p>
                                            {{ $data['module_name']['label'] ?? ucwords(str_replace(['-', '_'], ' ', $field)) }}
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        {{-- List — view permission --}}
                                        <li class="nav-item">
                                            <a href="{{ route('admin.contents.index', $field) }}"
                                                class="nav-link {{ request()->routeIs('admin.contents.index') && request()->route('module') == $field ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>List</p>
                                            </a>
                                        </li>

                                        {{-- Create — only if create permission --}}
                                        @if($canCreate)
                                            <li class="nav-item">
                                                <a href="{{ route('admin.contents.create', $field) }}"
                                                    class="nav-link {{ request()->routeIs('admin.contents.create') && request()->route('module') == $field ? 'active' : '' }}">
                                                    <i class="nav-icon bi bi-circle"></i>
                                                    <p>Create</p>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </li>

                            @endif

                        @endforeach

                        <li style="margin:0;border-bottom:1px solid var(--bs-border-color)"></li>

                        {{-- Users & Roles — only if roles.view permission --}}
                        @if(auth()->user()->hasPermission('roles.view'))

                            <li
                                class="nav-item nav-items {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people text-info"></i>
                                    <p>
                                        Access Control
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.roles.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Roles</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @endif
                        @if(auth()->user()->hasPermission('settings.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-gear text-info"></i>
                                    <p>Settings</p>
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasPermission('user_equirements.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.requirements.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-ui-checks text-info"></i>
                                    <p>User Requirements</p>
                                </a>
                            </li>

                        @endif
                        @if(auth()->user()->hasPermission('customers.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.customers.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people-fill text-info"></i>
                                    <p>Customers</p>
                                </a>
                            </li>

                        @endif
                        @if(auth()->user()->hasRole('super-admin'))
                            <li class="nav-item">
                                <a href="{{ route('admin.companies.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-person-check text-info"></i>
                                    <p>Company List</p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('admin.companies.profile') }}"
                                    class="nav-link {{ request()->routeIs('admin.companies.profile') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-building text-info"></i>
                                    <p>Company Profile</p>
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->hasPermission('message.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.contacts.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.contacts.index.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-chat text-info"></i>
                                    <p>Messages</p>
                                </a>
                            </li>
                        @endif
                        {{-- @if(auth()->user()->hasPermission('support-ticket.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.tickets.index') }}"
                                class="nav-link {{ request()->routeIs('admin.tickets.index.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-ticket-detailed text-info"></i>
                                <p>Support Ticket</p>
                            </a>
                        </li>
                        @endif --}}

                        @if(auth()->user()->isCompany())
                            <li class="nav-item">
                                <a href="{{ route('developer.guide') }}"
                                    class="nav-link {{ request()->routeIs('developer.guide') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-chat text-info"></i>
                                    <p>Developer Guide</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('admin.password.edit') }}"
                                class="nav-link {{ request()->routeIs('admin.password.edit.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-fill text-info"></i>
                                <p>Update Password</p>
                            </a>
                        </li>
                        {{-- Logout --}}
                        <li class="nav-item">
                            <a href="#" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon bi bi-box-arrow-left text-info"></i>
                                <p>Logout</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            @if(session('status'))
                <div class="container mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">

            <strong>
                Design & Developed By © <a target="_blank" href="https://bhaiya.digital">Bhaiya Digital</a>.
            </strong>
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{asset('/')}}backend/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('menuSearch');
            const menuItems = document.querySelectorAll('.nav-items');

            input.addEventListener('keyup', function () {
                const filter = input.value.toLowerCase();

                menuItems.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    if (text.includes(filter)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
<!--end::Body-->

</html>