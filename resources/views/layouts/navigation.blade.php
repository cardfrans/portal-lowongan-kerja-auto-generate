<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-slate-900 font-bold text-lg hover:text-blue-700 transition">
                        <span class="w-8 h-8 rounded-lg bg-blue-700 text-white flex items-center justify-center font-black text-sm">
                            UCC
                        </span>
                        <span class="tracking-tight">Career Portal</span>
                    </a>

                    @if(Auth::user()->isAdmin())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                            Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                            Perusahaan
                        </span>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard', 'admin.vacancies.*')">
                            Review Lowongan
                        </x-nav-link>
                        <x-nav-link :href="route('admin.logs.index')" :active="request()->routeIs('admin.logs.*')">
                            Audit Edit Lowongan
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('company.vacancies.index')" :active="request()->routeIs('company.vacancies.index', 'company.vacancies.show')">
                            Daftar Lowongan
                        </x-nav-link>
                        <x-nav-link :href="route('company.vacancies.create')" :active="request()->routeIs('company.vacancies.create')">
                            + Pasang Lowongan
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <span class="font-semibold">{{ Auth::user()->name }}</span>

                            <svg class="ms-2 -me-0.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-400">Masuk sebagai:</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            Pengaturan Akun
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-600 hover:text-rose-700">
                                Keluar (Log Out)
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200 bg-slate-50">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard', 'admin.vacancies.*')">
                    Review Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.logs.index')" :active="request()->routeIs('admin.logs.*')">
                    Audit Edit Lowongan
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('company.vacancies.index')" :active="request()->routeIs('company.vacancies.index', 'company.vacancies.show')">
                    Daftar Lowongan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('company.vacancies.create')" :active="request()->routeIs('company.vacancies.create')">
                    + Pasang Lowongan
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-200 px-4">
            <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Pengaturan Akun
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-rose-600">
                        Keluar (Log Out)
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
