<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <i class="bi bi-shield-lock-fill text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Gestión de Clubes (SaaS)') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ openCreateModal: false, openEditModal: false, editClub: { id: null, name: '', is_active: false, admin_name: '', admin_email: '', updateUrl: '' } }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de feedback -->
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center space-x-2 shadow-sm animate-fade-in-down">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Barra de Herramientas (Búsqueda y Nuevo Club) -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-950">Listado de Clubes Suscritos</h3>
                    <p class="text-xs text-gray-500">Administra las licencias de uso, módulos activos y el acceso para cada franquicia.</p>
                </div>
                <button @click="openCreateModal = true" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle-fill mr-2 text-lg"></i> Registrar Nuevo Club
                </button>
            </div>

            <!-- Table / Cards Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Club / Academia</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Módulos Activos (SaaS)</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($clubs as $club)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($club->logo)
                                                <img class="h-10 w-10 rounded-xl object-cover border border-gray-200 shadow-sm" src="{{ asset($club->logo) }}" alt="Logo {{ $club->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                    {{ strtoupper(substr($club->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $club->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter flex items-center mt-0.5">
                                                    <i class="bi bi-people mr-1"></i> {{ $club->users()->count() }} Usuarios Registrados
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($club->is_active)
                                            <span class="px-2.5 py-1 inline-flex text-[10px] font-black leading-none rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest">Activo</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-[10px] font-black leading-none rounded-full bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-widest">Suspendido</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($allModules as $module)
                                                @php
                                                    $hasModule = $club->modules->contains($module->id);
                                                @endphp
                                                <form action="{{ route('superadmin.clubs.toggleModule', $club->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="module_id" value="{{ $module->id }}">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold transition-all border
                                                        {{ $hasModule 
                                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' 
                                                            : 'bg-gray-50 text-gray-400 border-gray-200 hover:bg-gray-100' }}">
                                                        <i class="bi {{ $hasModule ? 'bi-check-circle-fill text-emerald-500' : 'bi-x-circle text-gray-400' }} mr-1.5 text-xs"></i>
                                                        {{ $module->name }}
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-3">
                                            <button @click="openEditModal = true; editClub = { id: '{{ $club->id }}', name: '{{ addslashes($club->name) }}', is_active: {{ $club->is_active ? 'true' : 'false' }}, admin_name: '{{ $club->admin ? addslashes($club->admin->name) : '' }}', admin_email: '{{ $club->admin ? addslashes($club->admin->email) : '' }}', updateUrl: '{{ route('superadmin.clubs.update', $club->id) }}' }" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Editar">
                                                <i class="bi bi-pencil-square text-lg"></i>
                                            </button>
                                            
                                            <form action="{{ route('superadmin.clubs.destroy', $club->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este club? Esta acción eliminará permanentemente todos sus usuarios y datos asociados.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 transition-colors" title="Eliminar">
                                                    <i class="bi bi-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No hay clubes registrados en la plataforma.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL DE CREACIÓN DE CLUB -->
            <div x-show="openCreateModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openCreateModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    
                    <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        
                        <form action="{{ route('superadmin.clubs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-900">Registrar Nuevo Club</h3>
                                    <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-500">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                
                                <div class="space-y-5">
                                    <!-- Datos Básicos del Club -->
                                    <div>
                                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-wider mb-3">1. Datos del Club / Academia</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de la Franquicia o Club</label>
                                                <input type="text" name="name" required placeholder="Ej: Club Deportivo Rodesa" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Logotipo (Opcional)</label>
                                                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 my-4"></div>

                                    <!-- Administrador / Propietario -->
                                    <div>
                                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-wider mb-3">2. Administrador / Director Propietario</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo del Director</label>
                                                <input type="text" name="admin_name" placeholder="Ej: Profesor de Prueba Rodesa" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico (Para Login)</label>
                                                <input type="email" name="admin_email" placeholder="Ej: director@rodesa.com" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña de Acceso</label>
                                                <input type="password" name="admin_password" placeholder="Mínimo 8 caracteres" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 italic text-center leading-tight">Nota: El usuario creado tendrá el rol de "Admin" para poder gestionar el club de manera autónoma.</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                                    Registrar Club
                                </button>
                                <button type="button" @click="openCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            </div>

            <!-- MODAL DE EDICIÓN DE CLUB -->
            <div x-show="openEditModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openEditModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    
                    <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        
                        <form :action="editClub.updateUrl" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-bold text-gray-900">Editar Club</h3>
                                    <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-500">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                
                                <div class="space-y-5">
                                    <!-- Datos Básicos del Club -->
                                    <div>
                                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-wider mb-3">1. Datos del Club / Academia</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de la Franquicia o Club</label>
                                                <input type="text" name="name" x-model="editClub.name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Logotipo (Opcional - dejar vacío para mantener actual)</label>
                                                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                                            </div>
                                            <div class="flex items-center space-x-3 mt-4">
                                                <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-model="editClub.is_active" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                <label for="edit_is_active" class="text-sm font-semibold text-gray-700">Club Activo (Permitir acceso a la plataforma)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 my-4"></div>

                                    <!-- Administrador / Propietario -->
                                    <div>
                                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-wider mb-3">2. Administrador / Director Propietario</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo del Director</label>
                                                <input type="text" name="admin_name" x-model="editClub.admin_name" placeholder="Ej: Profesor de Prueba Rodesa" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico (Para Login)</label>
                                                <input type="email" name="admin_email" x-model="editClub.admin_email" placeholder="Ej: director@rodesa.com" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña de Acceso (Nueva)</label>
                                                <input type="password" name="admin_password" placeholder="Dejar en blanco para mantener la actual" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                                    Guardar Cambios
                                </button>
                                <button type="button" @click="openEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
</x-app-layout>
