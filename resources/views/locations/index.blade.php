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

    <div class="py-8">
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

                                {{-- Activar / Desactivar --}}
                                <form action="{{ route('locations.update', $location) }}" method="POST">
                                    @csrf @method('PUT')
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
    </div>
</x-app-layout>
