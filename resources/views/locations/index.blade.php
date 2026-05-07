<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-geo-alt-fill text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">Gestión de Canchas</h2>
                    <p class="text-xs text-gray-400 font-medium">Ubicaciones donde se dictan las clases del club</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ openEditModal: false, editName: '', editDescription: '', editUrl: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- El layout principal ya muestra las alertas de session('success') y errores --}}

            {{-- Formulario nueva cancha --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-black text-gray-700 uppercase tracking-widest mb-4 flex items-center">
                    <i class="bi bi-plus-circle-fill mr-2 text-club-primary"></i> Agregar Nueva Cancha
                </h3>
                <form action="{{ route('locations.store') }}" method="POST" class="flex flex-col md:flex-row items-stretch md:items-end gap-4">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5">Nombre de la Cancha</label>
                        <input type="text" name="name" placeholder="Ej: San José, El Campín..."
                               class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-800 focus:ring-club-primary focus:border-club-primary" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5">Descripción (opcional)</label>
                        <input type="text" name="description" placeholder="Ubicación, características..."
                               class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-800 focus:ring-club-primary focus:border-club-primary">
                    </div>
                    <button type="submit"
                            class="px-6 py-3 bg-club-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-md">
                        <i class="bi bi-plus-lg mr-1"></i> Agregar
                    </button>
                </form>
            </div>

            {{-- Listado de canchas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        {{ $locations->count() }} cancha(s) registrada(s)
                    </span>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($locations as $location)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-5 gap-4 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center
                                    {{ $location->active ? 'bg-club-primary text-white shadow-lg shadow-blue-100' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="bi bi-geo-alt-fill text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 text-sm leading-tight">{{ $location->name }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5 tracking-tight">{{ $location->description ?: 'Sin descripción' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-50">
                                {{-- Badge de estado --}}
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider
                                    {{ $location->active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $location->active ? 'Activa' : 'Inactiva' }}
                                </span>

                                {{-- Botón Editar --}}
                                <button @click="openEditModal = true; editName = '{{ $location->name }}'; editDescription = '{{ $location->description }}'; editUrl = '{{ route('locations.update', $location) }}'"
                                        title="Editar"
                                        class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-all border border-blue-100 shadow-sm">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>

                                {{-- Activar / Desactivar --}}
                                <form action="{{ route('locations.update', $location) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="toggle_active" value="1">
                                    <button type="submit" title="{{ $location->active ? 'Desactivar' : 'Activar' }}"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl transition-all shadow-sm
                                            {{ $location->active ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100 border border-yellow-100' : 'bg-green-50 text-green-600 hover:bg-green-100 border border-green-100' }}">
                                        <i class="bi {{ $location->active ? 'bi-pause-circle-fill' : 'bi-play-circle-fill' }} text-lg"></i>
                                    </button>
                                </form>

                                {{-- Eliminar --}}
                                <form action="{{ route('locations.destroy', $location) }}" method="POST"
                                      onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar cancha', '¿Eliminar la cancha {{ $location->name }}? Los horarios asignados no se borrarán.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-400 rounded-xl hover:bg-red-100 hover:text-red-600 transition-all border border-red-100 shadow-sm">
                                        <i class="bi bi-trash-fill text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <i class="bi bi-geo-alt text-4xl text-gray-200"></i>
                            <p class="text-gray-400 font-bold text-sm mt-3">No hay canchas registradas.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Modal Editar --}}
        <div x-show="openEditModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <form :action="editUrl" method="POST">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Editar Cancha</h3>
                                <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de la Cancha</label>
                                    <input type="text" name="name" x-model="editName" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-club-primary focus:ring-club-primary transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción (opcional)</label>
                                    <input type="text" name="description" x-model="editDescription" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-club-primary focus:ring-club-primary transition-all">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-club-primary text-base font-bold text-white hover:opacity-90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
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
