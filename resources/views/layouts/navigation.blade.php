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

                <!-- Navigation Links -->
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
                                <!-- Header del dropdown -->
                                <div class="px-4 py-2.5 bg-club-primary flex items-center space-x-2">
                                    <i class="bi bi-grid-3x3-gap-fill text-white/80 text-xs"></i>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">Panel de Gestión</span>
                                </div>

                                <div class="py-1.5">
                                    <!-- Deportistas -->
                                    <a href="{{ route('students.index') }}"
                                       class="flex items-center px-4 py-2.5 text-sm font-semibold transition-colors
                                              {{ request()->routeIs('students.*') ? 'bg-blue-50 text-club-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center mr-3
                                                     {{ request()->routeIs('students.*') ? 'bg-club-primary text-white' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="bi bi-person-badge text-xs"></i>
                                        </span>
                                        {{ __('Deportistas') }}
                                    </a>

                                    <!-- Asistencias -->
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
                                        <!-- Usuarios (solo Admin) -->
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
                                        <!-- Canchas -->
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

            <!-- Settings Dropdown -->
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

                        <!-- Authentication -->
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Galería') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                {{ __('Mensualidades') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                {{ __('Horarios') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('programming.index')" :active="request()->routeIs('programming.*')">
                {{ __('Partidos') }}
            </x-responsive-nav-link>

            {{-- Sección de Gestión en móvil --}}
            @hasanyrole('Admin|Profesor')
                <div class="border-t border-gray-100 pt-2 mt-2">
                    <div class="px-4 py-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center">
                        <i class="bi bi-shield-lock-fill mr-1.5 text-club-primary"></i> Gestión
                    </div>

                    <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        <i class="bi bi-person-badge mr-2 text-club-primary"></i> {{ __('Deportistas') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">
                        <i class="bi bi-clipboard2-check mr-2 text-club-primary"></i> {{ __('Asistencias') }}
                    </x-responsive-nav-link>

                    @role('Admin')
                        <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            <i class="bi bi-people-fill mr-2 text-club-primary"></i> {{ __('Usuarios') }}
                            <span class="ml-2 text-[9px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-full uppercase">Admin</span>
                        </x-responsive-nav-link>
                    @endrole
                </div>
            @endhasanyrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
