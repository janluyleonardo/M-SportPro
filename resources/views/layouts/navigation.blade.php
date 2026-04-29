<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Galería') }}
                    </x-nav-link>

                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                        {{ __('Mensualidades') }}
                    </x-nav-link>

                    <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                        {{ __('Horarios') }}
                    </x-nav-link>

                    <x-nav-link :href="route('programming.index')" :active="request()->routeIs('programming.*')">
                        {{ __('Partidos') }}
                    </x-nav-link>

                    {{-- Dropdown de Gestión: visible para Admin y Profesor --}}
                    @hasanyrole('Admin|Profesor')
                        <div class="relative" x-data="{ openGestion: false }" @click.outside="openGestion = false">
                            <button
                                @click="openGestion = !openGestion"
                                :class="openGestion ? 'text-gray-900 border-club-primary' : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300'"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none h-16"
                            >
                                <i class="bi bi-shield-lock-fill mr-1.5 text-xs"></i>
                                {{ __('Gestión') }}
                                <svg class="ml-1.5 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openGestion }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Panel -->
                            <div
                                x-show="openGestion"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute left-0 top-full mt-1 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50"
                                style="display: none;"
                            >
                                <div class="px-4 py-2.5 bg-club-primary flex items-center space-x-2">
                                    <i class="bi bi-grid-3x3-gap-fill text-white/80 text-xs"></i>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">Panel de Gestión</span>
                                </div>

                                <div class="py-1.5">
                                    <a href="{{ route('students.index') }}"
                                       class="flex items-center px-4 py-2.5 text-sm font-semibold transition-colors
                                              {{ request()->routeIs('students.*') ? 'bg-blue-50 text-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-3
                                                     {{ request()->routeIs('students.*') ? 'bg-club-primary text-white' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="bi bi-person-badge text-xs"></i>
                                        </span>
                                        {{ __('Deportistas') }}
                                    </a>

                                    <a href="{{ route('attendances.index') }}"
                                       class="flex items-center px-4 py-2.5 text-sm font-semibold transition-colors
                                              {{ request()->routeIs('attendances.*') ? 'bg-blue-50 text-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-3
                                                     {{ request()->routeIs('attendances.*') ? 'bg-club-primary text-white' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="bi bi-clipboard2-check text-xs"></i>
                                        </span>
                                        {{ __('Asistencias') }}
                                    </a>

                                    @role('Admin')
                                        <div class="border-t border-gray-100 my-1.5"></div>
                                        <a href="{{ route('users.index') }}"
                                           class="flex items-center px-4 py-2.5 text-sm font-semibold transition-colors
                                                  {{ request()->routeIs('users.*') ? 'bg-yellow-50 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-3
                                                         {{ request()->routeIs('users.*') ? 'bg-club-secondary text-gray-900' : 'bg-gray-100 text-gray-500' }}">
                                                <i class="bi bi-people-fill text-xs"></i>
                                            </span>
                                            {{ __('Usuarios') }}
                                            <span class="ml-auto text-[9px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-full uppercase">Admin</span>
                                        </a>
                                        <a href="{{ route('locations.index') }}"
                                           class="flex items-center px-4 py-2.5 text-sm font-semibold transition-colors
                                                  {{ request()->routeIs('locations.*') ? 'bg-yellow-50 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-3
                                                         {{ request()->routeIs('locations.*') ? 'bg-club-secondary text-gray-900' : 'bg-gray-100 text-gray-500' }}">
                                                <i class="bi bi-geo-alt-fill text-xs"></i>
                                            </span>
                                            {{ __('Canchas') }}
                                            <span class="ml-auto text-[9px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-full uppercase">Admin</span>
                                        </a>
                                    @endrole
                                </div>
                            </div>
                        </div>
                    @endhasanyrole
                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ── Mobile Slide-In Panel ─────────────────────────────────── -->
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 sm:hidden"
        @click="open = false"
        style="display: none;"
    ></div>

    <!-- Panel -->
    <div
        x-show="open"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-72 bg-white shadow-2xl z-50 flex flex-col sm:hidden"
        style="display: none;"
    >
        <!-- Header del panel -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-club-primary">
            <div class="flex items-center space-x-2">
                <x-application-logo class="block h-7 w-auto" />
                <span class="text-sm font-black text-white uppercase tracking-wider">Menú</span>
            </div>
            <button @click="open = false" class="p-1.5 rounded-lg bg-white/20 text-white hover:bg-white/30 transition-colors">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Links principales -->
        <div class="flex-1 overflow-y-auto py-3">
            <div class="px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-images mr-3 text-base {{ request()->routeIs('dashboard') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                    {{ __('Galería') }}
                </a>

                <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('payments.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-cash-coin mr-3 text-base {{ request()->routeIs('payments.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                    {{ __('Mensualidades') }}
                </a>

                <a href="{{ route('schedules.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('schedules.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-calendar-week mr-3 text-base {{ request()->routeIs('schedules.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                    {{ __('Horarios') }}
                </a>

                <a href="{{ route('programming.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('programming.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-trophy mr-3 text-base {{ request()->routeIs('programming.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                    {{ __('Partidos') }}
                </a>
            </div>

            {{-- Sección de Gestión en móvil --}}
            @hasanyrole('Admin|Profesor')
                <div class="mt-4 px-3">
                    <div class="px-3 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center border-t border-gray-100 pt-4">
                        <i class="bi bi-shield-lock-fill mr-1.5 text-club-primary"></i> Gestión
                    </div>

                    <div class="space-y-1 mt-1">
                        <a href="{{ route('students.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('students.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="bi bi-person-badge mr-3 text-base {{ request()->routeIs('students.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                            {{ __('Deportistas') }}
                        </a>

                        <a href="{{ route('attendances.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('attendances.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="bi bi-clipboard2-check mr-3 text-base {{ request()->routeIs('attendances.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                            {{ __('Asistencias') }}
                        </a>

                        @role('Admin')
                            <a href="{{ route('users.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                                <i class="bi bi-people-fill mr-3 text-base {{ request()->routeIs('users.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                                {{ __('Usuarios') }}
                                <span class="ml-auto text-[9px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-full uppercase">Admin</span>
                            </a>

                            <a href="{{ route('locations.index') }}" class="flex items-center px-3 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('locations.*') ? 'bg-blue-50 text-club-primary border-l-4 border-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                                <i class="bi bi-geo-alt-fill mr-3 text-base {{ request()->routeIs('locations.*') ? 'text-club-primary' : 'text-gray-400' }}"></i>
                                {{ __('Canchas') }}
                                <span class="ml-auto text-[9px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-full uppercase">Admin</span>
                            </a>
                        @endrole
                    </div>
                </div>
            @endhasanyrole
        </div>

        <!-- Footer: Usuario -->
        <div class="border-t border-gray-100 p-4 bg-gray-50">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 bg-club-primary rounded-xl flex items-center justify-center text-white font-black text-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('profile.edit') }}" class="flex-1 text-center py-2 text-xs font-bold text-gray-600 bg-white rounded-xl border border-gray-200 hover:bg-gray-100 transition-all">
                    <i class="bi bi-person-gear mr-1"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2 text-xs font-bold text-red-500 bg-white rounded-xl border border-gray-200 hover:bg-red-50 transition-all">
                        <i class="bi bi-box-arrow-right mr-1"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- ─────────────────────────────────────────────────────────── -->
</nav>
