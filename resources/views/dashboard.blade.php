<x-app-layout>
    <x-slot name="header">
        @if(auth()->user()->is_super_admin)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-gradient-to-tr from-indigo-500 to-indigo-600 rounded-xl text-white shadow-md shadow-indigo-100">
                        <i class="bi bi-speedometer2 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                            {{ __('Panel de Control Global') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ __('Visualización del ecosistema de clubes y usuarios') }}</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('superadmin.clubs.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm transition duration-150 ease-in-out hover:shadow-indigo-100 hover:scale-[1.02]">
                        <i class="bi bi-gear-fill mr-2"></i>
                        {{ __('Gestionar Clubes') }}
                    </a>
                </div>
            </div>
        @else
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-images text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Galería del Club') }}
                </h2>
            </div>
        @endif
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(auth()->user()->is_super_admin)
                <!-- Tarjetas de Estadísticas Globales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tarjeta: Total Clubes -->
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider">{{ __('Total Clubes') }}</span>
                            <div class="text-3xl font-extrabold text-slate-800">{{ $clubs->count() }}</div>
                            <div class="text-xs text-emerald-600 flex items-center mt-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                {{ $clubs->where('is_active', true)->count() }} {{ __('Activos') }}
                            </div>
                        </div>
                        <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600">
                            <i class="bi bi-building text-2xl"></i>
                        </div>
                    </div>

                    <!-- Tarjeta: Total Usuarios -->
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider">{{ __('Total Usuarios') }}</span>
                            <div class="text-3xl font-extrabold text-slate-800">{{ $clubs->sum('users_count') }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ __('Registrados en la plataforma') }}
                            </div>
                        </div>
                        <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-600">
                            <i class="bi bi-people text-2xl"></i>
                        </div>
                    </div>

                    <!-- Tarjeta: Módulos Activos -->
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider">{{ __('Suscripciones Activas') }}</span>
                            <div class="text-3xl font-extrabold text-slate-800">
                                {{ $clubs->flatMap(fn($c) => $c->modules)->count() }}
                            </div>
                            <div class="text-xs text-indigo-600 mt-1 font-medium">
                                {{ __('Módulos habilitados en total') }}
                            </div>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-2xl text-purple-600">
                            <i class="bi bi-puzzle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Buscador Interactivo -->
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <i class="bi bi-search text-slate-400"></i>
                        </span>
                        <input type="text" id="club-search" placeholder="Buscar club por nombre, o buscar usuarios por nombre, correo, rol..." class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200 text-sm placeholder-slate-400 shadow-inner" />
                    </div>
                </div>

                <!-- Grilla de Clubes -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="clubs-grid">
                    @forelse($clubs as $club)
                        @php
                            // Crear la cadena de búsqueda consolidada
                            $searchTerms = strtolower($club->name) . ' ';
                            foreach($club->users as $u) {
                                $searchTerms .= strtolower($u->name) . ' ' . strtolower($u->email) . ' ';
                                if($u->roles->first()) {
                                    $searchTerms .= strtolower($u->roles->first()->name) . ' ';
                                }
                            }
                        @endphp
                        <div class="club-card bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between" data-search="{{ $searchTerms }}">
                            <div>
                                <!-- Encabezado de Tarjeta -->
                                <div class="flex items-start justify-between pb-4 border-b border-slate-100 mb-4">
                                    <div class="flex items-center space-x-3.5">
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shadow-inner">
                                            <img src="{{ $club->logo ? asset($club->logo) : asset('images/logo/LOGO.png') }}" alt="{{ $club->name }}" class="object-contain max-h-full max-w-full rounded-xl">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg text-slate-800 tracking-tight leading-snug">{{ $club->name }}</h3>
                                            <p class="text-xs text-slate-400 mt-0.5 flex items-center">
                                                <i class="bi bi-clock-history mr-1"></i>
                                                {{ __('Creado el') }} {{ $club->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($club->is_active)
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100">
                                                <span class="relative flex h-2 w-2 mr-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                {{ __('Activo') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-50 rounded-full border border-rose-100">
                                                <span class="h-2 w-2 mr-2 rounded-full bg-rose-400"></span>
                                                {{ __('Inactivo') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Módulos Habilitados -->
                                <div class="mb-5">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center">
                                        <i class="bi bi-cpu mr-1.5 text-sm"></i>
                                        {{ __('Módulos Contratados') }}
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($club->modules as $mod)
                                            @php
                                                $colorClass = match($mod->slug) {
                                                    'financial' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
                                                    'classes' => 'bg-blue-50 text-blue-700 border-blue-200/50',
                                                    'tournaments' => 'bg-amber-50 text-amber-700 border-amber-200/50',
                                                    default => 'bg-slate-50 text-slate-700 border-slate-200/50',
                                                };
                                                $iconClass = match($mod->slug) {
                                                    'financial' => 'bi-cash-coin',
                                                    'classes' => 'bi-calendar-check',
                                                    'tournaments' => 'bi-trophy',
                                                    default => 'bi-plugin',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $colorClass }}">
                                                <i class="bi {{ $iconClass }} mr-1.5 text-sm"></i>
                                                {{ $mod->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 flex items-center">
                                                <i class="bi bi-info-circle mr-1.5"></i>
                                                {{ __('Sin módulos contratados') }}
                                            </span>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Usuarios del Club -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                                        <span class="flex items-center">
                                            <i class="bi bi-people mr-1.5 text-sm"></i>
                                            {{ __('Usuarios Vinculados') }}
                                        </span>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold">
                                            {{ $club->users_count }} {{ __('total') }}
                                        </span>
                                    </h4>
                                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/40">
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                            <table class="min-w-full divide-y divide-slate-100">
                                                <thead class="bg-slate-50 sticky top-0 z-10">
                                                    <tr>
                                                        <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-slate-500 tracking-wider bg-slate-50">{{ __('Usuario') }}</th>
                                                        <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-slate-500 tracking-wider bg-slate-50">{{ __('Correo') }}</th>
                                                        <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-slate-500 tracking-wider bg-slate-50">{{ __('Rol') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100">
                                                    @forelse($club->users as $u)
                                                        @php
                                                            $role = $u->roles->first();
                                                            $roleName = $role ? $role->name : __('Sin Rol');
                                                            $roleBadgeColor = match($roleName) {
                                                                'Admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
                                                                'Profesor' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                                                'Padre' => 'bg-teal-50 text-teal-700 border-teal-200/60',
                                                                'Deportista' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                                                default => 'bg-slate-50 text-slate-600 border-slate-200/60',
                                                            };
                                                        @endphp
                                                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                                            <td class="px-4 py-2 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-50 to-indigo-100/50 border border-slate-100 flex items-center justify-center text-[10px] font-bold text-indigo-600">
                                                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                                                    </div>
                                                                    <span class="ml-2 text-xs font-bold text-slate-700 block truncate max-w-[120px]">{{ $u->name }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-2 whitespace-nowrap text-xs text-slate-500">
                                                                <span class="block truncate max-w-[150px]" title="{{ $u->email }}">{{ $u->email }}</span>
                                                            </td>
                                                            <td class="px-4 py-2 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $roleBadgeColor }}">
                                                                    {{ $roleName }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="px-4 py-8 text-center text-xs text-slate-400 bg-white">
                                                                <div class="flex flex-col items-center justify-center space-y-1.5 py-3">
                                                                    <i class="bi bi-people text-2xl text-slate-300"></i>
                                                                    <span>{{ __('No hay usuarios en este club.') }}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white border border-slate-100 rounded-3xl p-12 text-center text-slate-500 shadow-sm flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-50 rounded-full text-slate-400">
                                <i class="bi bi-building-x text-3xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800">{{ __('No hay clubes registrados') }}</h3>
                            <p class="text-sm text-slate-400 max-w-sm leading-relaxed">{{ __('Puedes registrar un nuevo club utilizando la sección de gestión en la esquina superior derecha.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- CSS Personalizado para Scrollbar y Efectos -->
                <style>
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 6px;
                        height: 6px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-track {
                        background: transparent;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 9999px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                        background: #94a3b8;
                    }
                </style>

                <!-- JS para Filtro de Búsqueda -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const searchInput = document.getElementById('club-search');
                        const clubCards = document.querySelectorAll('.club-card');
                        const gridContainer = document.getElementById('clubs-grid');

                        // Crear elemento para estado sin resultados
                        const noResults = document.createElement('div');
                        noResults.className = 'col-span-full bg-white border border-slate-100 rounded-3xl p-12 text-center text-slate-500 shadow-sm flex flex-col items-center justify-center space-y-3 hidden';
                        noResults.innerHTML = `
                            <div class="p-4 bg-slate-50 rounded-full text-slate-400">
                                <i class="bi bi-search text-3xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800">Sin resultados</h3>
                            <p class="text-sm text-slate-400 max-w-sm">No encontramos clubes o usuarios que coincidan con tu búsqueda.</p>
                        `;
                        gridContainer.appendChild(noResults);

                        searchInput.addEventListener('input', function () {
                            const query = searchInput.value.toLowerCase().trim();
                            let hasMatches = false;

                            clubCards.forEach(card => {
                                const searchTerms = card.getAttribute('data-search') || '';
                                if (searchTerms.includes(query)) {
                                    card.style.display = '';
                                    hasMatches = true;
                                } else {
                                    card.style.display = 'none';
                                }
                            });

                            if (hasMatches) {
                                noResults.classList.add('hidden');
                            } else {
                                noResults.classList.remove('hidden');
                            }
                        });
                    });
                </script>
            @else
                <!-- Sección de Fotos -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="bi bi-camera-fill text-club-primary mr-3 text-2xl"></i>
                            {{__('Galery_photos')}}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Facebook_photos_subtitle')}}</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Foto 1 -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_PHOTO_1_SRC', 'https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FClubdeportivoSaprix%2Fposts%2Fpfbid02BWicaUPVXAcZE36HCAQS7X2a57ZTpCBRzvihEWfNMZkEcYaS7TnHjzDYXnouSJw4l&show_text=true&width=500') }}"
                                    width="500" height="{{ env('FB_PHOTO_1_HEIGHT', '392') }}"
                                    style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            </div>
                            <!-- Foto 2 -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_PHOTO_2_SRC', 'https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FClubdeportivoSaprix%2Fposts%2Fpfbid0DBhRyfVXM3NUkhGg5rLtuQDEDwnA2mTXpJbGUJBbj7GnsNQEz5e4Yg9AGPxdmca9l&show_text=true&width=500') }}"
                                    width="500" height="{{ env('FB_PHOTO_2_HEIGHT', '462') }}"
                                    style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            </div>
                            <!-- Foto 3 (Restaurada) -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_PHOTO_3_SRC', 'https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FClubdeportivoSaprix%2Fposts%2Fpfbid0US5eVdhMSb6UCczEKtD4xrMsDAg61xrkMbc7SwHze8YRsaBG9775GpY6bMX6sUqvl&show_text=true&width=500') }}"
                                    width="500" height="{{ env('FB_PHOTO_3_HEIGHT', '648') }}"
                                    style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            </div>
                            <!-- Foto 4 (Restaurada) -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_PHOTO_4_SRC', 'https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FClubdeportivoSaprix%2Fposts%2Fpfbid02mX422euFshrFrBXst9jv3Jegjyt4uipVvY3yFAjnqKsZEZ1zWCqtWfBPZHXUEyahl&show_text=true&width=500') }}"
                                    width="500" height="{{ env('FB_PHOTO_4_HEIGHT', '448') }}"
                                    style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Videos -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="bi bi-play-btn-fill text-red-500 mr-3 text-2xl"></i>
                            {{__('Galery_videos')}}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Facebook_videos_subtitle')}}</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Video 1 -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_VIDEO_1_SRC', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F838949451987583%2F&show_text=false&width=267&t=0') }}"
                                    width="{{ env('FB_VIDEO_1_WIDTH', '267') }}"
                                    height="{{ env('FB_VIDEO_1_HEIGHT', '476') }}" style="border:none;overflow:hidden"
                                    scrolling="no" frameborder="0" allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                    allowFullScreen="true"></iframe>
                            </div>
                            <!-- Video 2 -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_VIDEO_2_SRC', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F3034697043388194%2F&show_text=false&width=267&t=0') }}"
                                    width="{{ env('FB_VIDEO_2_WIDTH', '267') }}"
                                    height="{{ env('FB_VIDEO_2_HEIGHT', '476') }}" style="border:none;overflow:hidden"
                                    scrolling="no" frameborder="0" allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                    allowFullScreen="true"></iframe>
                            </div>
                            <!-- Video 3 (Restaurado) -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_VIDEO_3_SRC', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F4495617584057591%2F&show_text=false&width=267&t=0') }}"
                                    width="{{ env('FB_VIDEO_3_WIDTH', '267') }}"
                                    height="{{ env('FB_VIDEO_3_HEIGHT', '476') }}" style="border:none;overflow:hidden"
                                    scrolling="no" frameborder="0" allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                    allowFullScreen="true"></iframe>
                            </div>
                            <!-- Video 4 (Restaurado) -->
                            <div
                                class="rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-inner p-2 h-auto w-full flex justify-center">
                                <iframe
                                    src="{{ env('FB_VIDEO_4_SRC', 'https://www.facebook.com/plugins/video.php?height=476&href=https%3A%2F%2Fwww.facebook.com%2Freel%2F1849891552263770%2F&show_text=false&width=267&t=0') }}"
                                    width="{{ env('FB_VIDEO_4_WIDTH', '267') }}"
                                    height="{{ env('FB_VIDEO_4_HEIGHT', '476') }}" style="border:none;overflow:hidden"
                                    scrolling="no" frameborder="0" allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                    allowFullScreen="true"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Instagram (Restaurada) -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                    <div class="p-6 sm:px-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="bi bi-instagram text-pink-600 mr-3 text-2xl"></i>
                            {{__('Instagram')}}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-9">{{__('Instagram_subtitle')}}</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 justify-items-center">
                            <!-- Instagram 1 -->
                            <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                                <blockquote class="instagram-media" data-instgrm-captioned
                                    data-instgrm-permalink="{{ env('IG_POST_1_URL', 'https://www.instagram.com/p/DWhQBCojqLy/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                    data-instgrm-version="14"
                                    style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                                    <div style="padding:16px;"> <a
                                            href="{{ env('IG_POST_1_URL', 'https://www.instagram.com/p/DWhQBCojqLy/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                            style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                            target="_blank">
                                            <div style=" display: flex; flex-direction: row; align-items: center;">
                                                <div
                                                    style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
                                                </div>
                                                <div
                                                    style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="padding: 19% 0;"></div>
                                            <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg
                                                    width="50px" height="50px" viewBox="0 0 60 60" version="1.1"
                                                    xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                            <g>
                                                                <path
                                                                    d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg></div>
                                            <div style="padding-top: 8px;">
                                                <div
                                                    style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                                    Ver esta publicación en Instagram</div>
                                            </div>
                                            <div style="padding: 12.5% 0;"></div>
                                            <div
                                                style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                                <div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);">
                                                    </div>
                                                </div>
                                                <div style="margin-left: 8px;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)">
                                                    </div>
                                                </div>
                                                <div style="margin-left: auto;">
                                                    <div
                                                        style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;">
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;">
                                                </div>
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;">
                                                </div>
                                            </div>
                                        </a>
                                        <p
                                            style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                            <a href="{{ env('IG_POST_1_URL', 'https://www.instagram.com/p/DWhQBCojqLy/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                                style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;"
                                                target="_blank">Una publicación compartida de Club Deportivo Rodesa
                                                (@clubdeportivorodesa)</a>
                                        </p>
                                    </div>
                                </blockquote>
                            </div>
                            <!-- Instagram 2 -->
                            <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                                <blockquote class="instagram-media" data-instgrm-captioned
                                    data-instgrm-permalink="{{ env('IG_POST_2_URL', 'https://www.instagram.com/p/DUlwuiiDejH/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                    data-instgrm-version="14"
                                    style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                                    <div style="padding:16px;"> <a
                                            href="{{ env('IG_POST_2_URL', 'https://www.instagram.com/p/DUlwuiiDejH/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                            style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                            target="_blank">
                                            <div style=" display: flex; flex-direction: row; align-items: center;">
                                                <div
                                                    style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
                                                </div>
                                                <div
                                                    style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="padding: 19% 0;"></div>
                                            <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg
                                                    width="50px" height="50px" viewBox="0 0 60 60" version="1.1"
                                                    xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                            <g>
                                                                <path
                                                                    d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg></div>
                                            <div style="padding-top: 8px;">
                                                <div
                                                    style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                                    Ver esta publicación en Instagram</div>
                                            </div>
                                            <div style="padding: 12.5% 0;"></div>
                                            <div
                                                style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                                <div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);">
                                                    </div>
                                                </div>
                                                <div style="margin-left: 8px;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)">
                                                    </div>
                                                </div>
                                                <div style="margin-left: auto;">
                                                    <div
                                                        style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;">
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;">
                                                    </div>
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;">
                                                </div>
                                            </div>
                                        </a>
                                        <p
                                            style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                            <a href="{{ env('IG_POST_2_URL', 'https://www.instagram.com/p/DUlwuiiDejH/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                                style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;"
                                                target="_blank">Una publicación compartida de Club Deportivo Rodesa
                                                (@clubdeportivorodesa)</a>
                                        </p>
                                    </div>
                                </blockquote>
                                <script async src="//www.instagram.com/embed.js"></script>
                            </div>
                            <!-- Instagram 3 -->
                            <div class="w-full flex justify-center overflow-hidden rounded-xl border border-gray-100">
                                <blockquote class="instagram-media" data-instgrm-captioned
                                    data-instgrm-permalink="{{ env('IG_POST_3_URL', 'https://www.instagram.com/p/DUTuAjmjx-6/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                    data-instgrm-version="14"
                                    style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                                    <div style="padding:16px;"> <a
                                            href="{{ env('IG_POST_3_URL', 'https://www.instagram.com/p/DUTuAjmjx-6/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                            style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                            target="_blank">
                                            <div style=" display: flex; flex-direction: row; align-items: center;">
                                                <div
                                                    style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
                                                </div>
                                                <div
                                                    style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="padding: 19% 0;"></div>
                                            <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg
                                                    width="50px" height="50px" viewBox="0 0 60 60" version="1.1"
                                                    xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                            <g>
                                                                <path
                                                                    d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg></div>
                                            <div style="padding-top: 8px;">
                                                <div
                                                    style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                                    Ver esta publicación en Instagram</div>
                                            </div>
                                            <div style="padding: 12.5% 0;"></div>
                                            <div
                                                style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                                <div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;">
                                                    </div>
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);">
                                                    </div>
                                                </div>
                                                <div style="margin-left: 8px;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)">
                                                    </div>
                                                </div>
                                                <div style="margin-left: auto;">
                                                    <div
                                                        style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);">
                                                    </div>
                                                    <div
                                                        style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;">
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;">
                                                </div>
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;">
                                                </div>
                                            </div>
                                        </a>
                                        <p
                                            style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                            <a href="{{ env('IG_POST_3_URL', 'https://www.instagram.com/p/DUTuAjmjx-6/?utm_source=ig_embed&amp;utm_campaign=loading') }}"
                                                style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;"
                                                target="_blank">Una publicación compartida de Club Deportivo Rodesa
                                                (@clubdeportivorodesa)</a></p>
                                    </div>
                                </blockquote>
                                <script async src="//www.instagram.com/embed.js"></script>
                            </div>
                        </div>
                    </div>
                    <!-- Instagram Script -->
                    <script async src="//www.instagram.com/embed.js"></script>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>