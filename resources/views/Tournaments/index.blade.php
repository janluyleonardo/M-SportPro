<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <i class="bi bi-flag text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Gestión de Torneos') }}
                </h2>
            </div>
            @hasanyrole('Admin|SubAdmin|Profesor')
                <div x-data="{}">
                    <button @click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-tournament' }))" class="inline-flex items-center px-4 py-2 bg-club-primary border border-transparent rounded-xl font-bold text-sm text-white hover:opacity-90 transition-all shadow-md">
                        <i class="bi bi-plus-circle mr-2"></i> Nuevo Torneo
                    </button>
                </div>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="py-12" x-data="tournamentApp(@js($studentList))">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tournaments as $tournament)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex flex-wrap gap-2">
                                    <div class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ $tournament->category ?? 'General' }}
                                    </div>
                                    @if(auth()->user()->is_super_admin)
                                        <div class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                            {{ $tournament->club->name ?? 'N/A' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex space-x-1">
                                    <button @click="openEdit({{ $tournament->toJson() }}, @js($tournament->students->pluck('id')))" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @hasanyrole('Admin|Profesor')
                                    <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" class="inline" id="delete-form-{{ $tournament->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="confirmAction(document.getElementById('delete-form-{{ $tournament->id }}'), '¿Eliminar Torneo?', 'Se eliminará el torneo y su historial de pagos asociados.')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endhasanyrole
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-black text-gray-900 mb-2 group-hover:text-club-primary transition-colors">{{ $tournament->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-6">{{ $tournament->description ?? 'Sin descripción.' }}</p>
                            
                            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Programaciones</span>
                                    <span class="text-sm font-bold text-gray-700">{{ $tournament->programmings_count }} partidos</span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Costo Total Match</span>
                                    <span class="text-sm font-bold text-indigo-600">
                                        ${{ number_format($tournament->costo_total_inscripcion + $tournament->costo_total_arbitraje, 0) }}
                                    </span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estado</span>
                                    <span class="text-sm font-bold {{ $tournament->status === 'activo' ? 'text-green-500' : 'text-gray-400' }} flex items-center">
                                        <i class="bi bi-circle-fill text-[6px] mr-1.5"></i> {{ ucfirst($tournament->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-50 flex gap-2">
                            <a href="{{ route('tournaments.payments', $tournament) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 hover:bg-gray-100 transition-all shadow-sm">
                                <i class="bi bi-cash-coin mr-2 text-green-500"></i> Control de Pagos
                            </a>
                        </div>
                    </div>
                @endforeach
                
                @if($tournaments->isEmpty())
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-flag text-4xl text-gray-200"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">No hay torneos creados</h4>
                        <p class="text-gray-500 mb-6">Empieza creando tu primer torneo para llevar el control de pagos.</p>
                        <button @click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-tournament' }))" class="inline-flex items-center px-6 py-3 bg-club-primary rounded-xl font-bold text-white shadow-lg">
                            Crear Torneo
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Modal -->
        <x-modal name="create-tournament" focusable>
            <form action="{{ route('tournaments.store') }}" method="POST" class="p-8" @submit="submitting = true">
                @csrf
                <h2 class="text-2xl font-black text-gray-900 mb-6">Nuevo Torneo</h2>
                
                <div class="space-y-6">
                    @if(auth()->user()->is_super_admin && $clubs->isNotEmpty())
                    <div>
                        <x-input-label for="club_id" value="Club" class="font-bold text-gray-700 mb-1" />
                        <select id="club_id" name="club_id" x-model="selectedClubId" @change="onClubChange" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-950 bg-gray-50 focus:bg-white transition-all" required>
                            <option value="">Seleccione el Club...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <x-input-label for="name" value="Nombre del Torneo" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="name" name="name" type="text" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" placeholder="Ej: Copa Bogotanos 2024" required />
                    </div>
                    
                    <div>
                        <x-category-select :categories="$categories" name="category" label="Categoría" placeholder="Ej: Sub-15 / Mayores" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="costo_total_inscripcion" value="Total Inscripción" class="font-bold text-gray-700 mb-1" />
                            <x-text-input id="costo_total_inscripcion" name="costo_total_inscripcion" type="number" step="0.01" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" placeholder="Costo total" />
                        </div>
                        <div>
                            <x-input-label for="costo_arbitraje_partido" value="Arbitraje por Partido" class="font-bold text-gray-700 mb-1" />
                            <x-text-input id="costo_arbitraje_partido" name="costo_arbitraje_partido" type="number" step="0.01" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" placeholder="Costo por fecha" />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="description" value="Descripción" class="font-bold text-gray-700 mb-1" />
                        <textarea id="description" name="description" rows="3" class="block w-full rounded-2xl border-gray-200 focus:border-club-primary focus:ring-club-primary" placeholder="Detalles adicionales del torneo..."></textarea>
                    </div>

                    <!-- Students Transfer List (Create) -->
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Asociar Deportistas al Torneo</label>
                        
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="student_ids[]" :value="id">
                        </template>

                        <div class="w-full flex flex-col md:flex-row gap-4 items-stretch">
                            <!-- Disponibles -->
                            <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider">
                                        Disponibles <span class="font-normal text-[10px] text-gray-500">(Mostrando <span x-text="filteredAvailable.length"></span>)</span>
                                    </span>
                                    <button type="button" @click="moveAllToSelected" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-black uppercase tracking-widest">Añadir Visibles <i class="bi bi-chevron-double-right"></i></button>
                                </div>
                                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="searchLeft" placeholder="Buscar nombre o categoría..." class="w-full text-[11px] pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div class="flex-1 overflow-y-auto min-h-[15rem] max-h-[15rem] p-2 space-y-1 bg-gray-50/30">
                                    <template x-for="player in filteredAvailable" :key="player.id">
                                        <div @click="moveToSelected(player)" class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                            <div><div class="text-xs font-bold text-gray-700 group-hover:text-indigo-700" x-text="player.name"></div></div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-0.5 text-[9px] font-black rounded-full bg-gray-200 text-gray-600 group-hover:bg-indigo-200 group-hover:text-indigo-800" x-text="player.category"></span>
                                                <i class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="filteredAvailable.length === 0" class="text-center py-8 text-xs text-gray-400 italic">No hay deportistas disponibles</div>
                                </div>
                            </div>

                            <!-- Seleccionados -->
                            <div class="flex-1 flex flex-col border border-indigo-100 rounded-xl overflow-hidden shadow-sm bg-white">
                                <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                                    <span class="font-bold text-xs text-indigo-900 uppercase tracking-wider">Asociados (<span x-text="selected.length"></span>)</span>
                                    <button type="button" @click="moveAllToAvailable" class="text-[10px] text-red-600 hover:text-red-800 font-black uppercase tracking-widest"><i class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                                </div>
                                <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="searchRight" placeholder="Buscar asociado..." class="w-full text-[11px] pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div class="flex-1 overflow-y-auto min-h-[15rem] max-h-[15rem] p-2 space-y-1 bg-white">
                                    <template x-for="player in filteredSelected" :key="player.id">
                                        <div @click="moveToAvailable(player)" class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                            <div class="flex items-center space-x-2">
                                                <i class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg transition-colors"></i>
                                                <div class="text-xs font-bold text-green-800 group-hover:text-red-700" x-text="player.name"></div>
                                            </div>
                                            <span class="px-2 py-0.5 text-[9px] font-black rounded-full bg-green-200 text-green-800 group-hover:bg-red-200 group-hover:text-red-800" x-text="player.category"></span>
                                        </div>
                                    </template>
                                    <div x-show="selected.length === 0" class="flex flex-col items-center justify-center h-full text-center p-4">
                                        <p class="text-xs text-gray-400 italic">No hay deportistas asociados todavía.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="submitting" class="px-6 py-3 bg-club-primary text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-lg hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center">
                        <template x-if="submitting">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="submitting ? 'Creando...' : 'Crear Torneo'"></span>
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Edit Modal -->
        <x-modal name="edit-tournament" focusable>
            <form x-show="editingTournament" :action="'{{ route('tournaments.update', 999999) }}'.replace('999999', editingTournament?.id)" method="POST" class="p-8" @submit="submitting = true">
                @csrf
                @method('PUT')
                <h2 class="text-2xl font-black text-gray-900 mb-6">Editar Torneo</h2>
                
                <div class="space-y-6">
                    @if(auth()->user()->is_super_admin && $clubs->isNotEmpty())
                    <div>
                        <x-input-label for="edit_club_id" value="Club" class="font-bold text-gray-700 mb-1" />
                        <select id="edit_club_id" name="club_id" x-model="editSelectedClubId" @change="onEditClubChange" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-950 bg-gray-50 focus:bg-white transition-all" required>
                            <option value="">Seleccione el Club...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <x-input-label for="edit_name" value="Nombre del Torneo" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="edit_name" name="name" type="text" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" x-model="editingTournament.name" required />
                    </div>
                    
                    <div>
                        <x-category-select :categories="$categories" name="category" label="Categoría" placeholder="Ej: Sub-15 / Mayores" modelName="editCategory" />
                    </div>

                    <div>
                        <x-input-label for="edit_status" value="Estado" class="font-bold text-gray-700 mb-1" />
                        <select id="edit_status" name="status" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" x-model="editingTournament.status">
                            <option value="activo">Activo</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="edit_costo_total_inscripcion" value="Total Inscripción" class="font-bold text-gray-700 mb-1" />
                            <x-text-input id="edit_costo_total_inscripcion" name="costo_total_inscripcion" type="number" step="0.01" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" x-model="editingTournament.costo_total_inscripcion" />
                        </div>
                        <div>
                            <x-input-label for="edit_costo_arbitraje_partido" value="Arbitraje por Partido" class="font-bold text-gray-700 mb-1" />
                            <x-text-input id="edit_costo_arbitraje_partido" name="costo_arbitraje_partido" type="number" step="0.01" class="block w-full rounded-2xl border-gray-200 focus:ring-club-primary" x-model="editingTournament.costo_arbitraje_partido" />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="edit_description" value="Descripción" class="font-bold text-gray-700 mb-1" />
                        <textarea id="edit_description" name="description" rows="3" class="block w-full rounded-2xl border-gray-200 focus:border-club-primary focus:ring-club-primary" x-model="editingTournament.description"></textarea>
                    </div>

                    <!-- Students Transfer List (Edit) -->
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Gestionar Deportistas del Torneo</label>
                        
                        <template x-for="id in editSelectedIds" :key="id">
                            <input type="hidden" name="student_ids[]" :value="id">
                        </template>

                        <div class="w-full flex flex-col md:flex-row gap-4 items-stretch">
                            <!-- Disponibles -->
                            <div class="flex-1 flex flex-col border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider">
                                        Disponibles <span class="font-normal text-[10px] text-gray-500">(Mostrando <span x-text="editFilteredAvailable.length"></span>)</span>
                                    </span>
                                    <button type="button" @click="editMoveAllToSelected" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-black uppercase tracking-widest">Añadir Visibles <i class="bi bi-chevron-double-right"></i></button>
                                </div>
                                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="editSearchLeft" placeholder="Buscar nombre o categoría..." class="w-full text-[11px] pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div class="flex-1 overflow-y-auto min-h-[15rem] max-h-[15rem] p-2 space-y-1 bg-gray-50/30">
                                    <template x-for="player in editFilteredAvailable" :key="player.id">
                                        <div @click="editMoveToSelected(player)" class="flex justify-between items-center p-2 rounded-lg hover:bg-indigo-50 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors group">
                                            <div><div class="text-xs font-bold text-gray-700 group-hover:text-indigo-700" x-text="player.name"></div></div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-0.5 text-[9px] font-black rounded-full bg-gray-200 text-gray-600 group-hover:bg-indigo-200 group-hover:text-indigo-800" x-text="player.category"></span>
                                                <i class="bi bi-arrow-right-short text-gray-300 group-hover:text-indigo-500 text-lg"></i>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="editFilteredAvailable.length === 0" class="text-center py-8 text-xs text-gray-400 italic">No hay deportistas disponibles</div>
                                </div>
                            </div>

                            <!-- Seleccionados -->
                            <div class="flex-1 flex flex-col border border-indigo-100 rounded-xl overflow-hidden shadow-sm bg-white">
                                <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                                    <span class="font-bold text-xs text-indigo-900 uppercase tracking-wider">Asociados (<span x-text="editSelected.length"></span>)</span>
                                    <button type="button" @click="editMoveAllToAvailable" class="text-[10px] text-red-600 hover:text-red-800 font-black uppercase tracking-widest"><i class="bi bi-chevron-double-left"></i> Quitar Todos</button>
                                </div>
                                <div class="p-2 border-b border-indigo-50 bg-indigo-50/30">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-model="editSearchRight" placeholder="Buscar asociado..." class="w-full text-[11px] pl-8 pr-3 py-1.5 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div class="flex-1 overflow-y-auto min-h-[15rem] max-h-[15rem] p-2 space-y-1 bg-white">
                                    <template x-for="player in editFilteredSelected" :key="player.id">
                                        <div @click="editMoveToAvailable(player)" class="flex justify-between items-center p-2 rounded-lg bg-green-50 hover:bg-red-50 cursor-pointer border border-green-100 hover:border-red-100 transition-colors group">
                                            <div class="flex items-center space-x-2">
                                                <i class="bi bi-arrow-left-short text-transparent group-hover:text-red-500 text-lg transition-colors"></i>
                                                <div class="text-xs font-bold text-green-800 group-hover:text-red-700" x-text="player.name"></div>
                                            </div>
                                            <span class="px-2 py-0.5 text-[9px] font-black rounded-full bg-green-200 text-green-800 group-hover:bg-red-200 group-hover:text-red-800" x-text="player.category"></span>
                                        </div>
                                    </template>
                                    <div x-show="editSelected.length === 0" class="flex flex-col items-center justify-center h-full text-center p-4">
                                        <p class="text-xs text-gray-400 italic">No hay deportistas asociados todavía.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="submitting" class="px-6 py-3 bg-club-primary text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-lg hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center">
                        <template x-if="submitting">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="submitting ? 'Guardando...' : 'Guardar Cambios'"></span>
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        function tournamentApp(studentList) {
            return {
                allStudents: studentList.map(s => ({ id: s.id, name: s.nomDeportista, category: s.Categoria, clubId: s.club_id })),
                editingTournament: null,
                openCreate: false,
                submitting: false,
                editCategory: '',

                // Create Transfer List
                selectedClubId: '',
                onClubChange() {
                    const clubId = this.selectedClubId;
                    if (clubId) {
                        this.available = this.allStudents.filter(s => s.clubId == clubId);
                    } else {
                        this.available = [...this.allStudents];
                    }
                    this.selected = [];
                },
                searchLeft: '',
                searchRight: '',
                available: [],
                selected: [],
                get selectedIds() { return this.selected.map(p => p.id); },
                get filteredAvailable() {
                    let f = this.available;
                    if (this.searchLeft) {
                        const s = this.searchLeft.toLowerCase();
                        f = f.filter(p => p.name.toLowerCase().includes(s) || (p.category && p.category.toString().toLowerCase().includes(s)));
                    }
                    return f.slice(0, 50);
                },
                get filteredSelected() {
                    let f = this.selected;
                    if (this.searchRight) {
                        const s = this.searchRight.toLowerCase();
                        f = f.filter(p => p.name.toLowerCase().includes(s) || (p.category && p.category.toString().toLowerCase().includes(s)));
                    }
                    return f;
                },
                moveToSelected(p) {
                    this.selected.push(p);
                    this.available = this.available.filter(x => x.id !== p.id);
                },
                moveToAvailable(p) {
                    this.available.push(p);
                    this.selected = this.selected.filter(x => x.id !== p.id);
                },
                moveAllToSelected() {
                    const toMove = this.filteredAvailable;
                    this.selected = [...this.selected, ...toMove];
                    const ids = toMove.map(m => m.id);
                    this.available = this.available.filter(a => !ids.includes(a.id));
                },
                moveAllToAvailable() {
                    this.available = [...this.available, ...this.selected];
                    this.selected = [];
                },

                // Edit Transfer List
                editSelectedClubId: '',
                onEditClubChange() {
                    const clubId = this.editSelectedClubId;
                    if (clubId) {
                        this.editAvailable = this.allStudents.filter(s => s.clubId == clubId);
                    } else {
                        this.editAvailable = [...this.allStudents];
                    }
                    this.editSelected = [];
                },
                editSearchLeft: '',
                editSearchRight: '',
                editAvailable: [],
                editSelected: [],
                get editSelectedIds() { return this.editSelected.map(p => p.id); },
                get editFilteredAvailable() {
                    let f = this.editAvailable;
                    if (this.editSearchLeft) {
                        const s = this.editSearchLeft.toLowerCase();
                        f = f.filter(p => p.name.toLowerCase().includes(s) || (p.category && p.category.toString().toLowerCase().includes(s)));
                    }
                    return f.slice(0, 50);
                },
                get editFilteredSelected() {
                    let f = this.editSelected;
                    if (this.editSearchRight) {
                        const s = this.editSearchRight.toLowerCase();
                        f = f.filter(p => p.name.toLowerCase().includes(s) || (p.category && p.category.toString().toLowerCase().includes(s)));
                    }
                    return f;
                },
                editMoveToSelected(p) {
                    this.editSelected.push(p);
                    this.editAvailable = this.editAvailable.filter(x => x.id !== p.id);
                },
                editMoveToAvailable(p) {
                    this.editAvailable.push(p);
                    this.editSelected = this.editSelected.filter(x => x.id !== p.id);
                },
                editMoveAllToSelected() {
                    const toMove = this.editFilteredAvailable;
                    this.editSelected = [...this.editSelected, ...toMove];
                    const ids = toMove.map(m => m.id);
                    this.editAvailable = this.editAvailable.filter(a => !ids.includes(a.id));
                },
                editMoveAllToAvailable() {
                    this.editAvailable = [...this.editAvailable, ...this.editSelected];
                    this.editSelected = [];
                },

                init() {
                    this.available = [...this.allStudents];
                    window.addEventListener('open-modal', (e) => {
                        if (e.detail === 'create-tournament') {
                            this.selectedClubId = '';
                            this.available = [...this.allStudents];
                            this.selected = [];
                            this.submitting = false;
                        }
                    });
                },

                openEdit(tournament, studentIds) {
                    this.editingTournament = tournament;
                    this.editCategory = tournament.category || '';
                    this.editSelectedClubId = tournament.club_id || '';
                    this.editSelected = this.allStudents.filter(s => studentIds.includes(s.id));
                    
                    if (this.editSelectedClubId) {
                        this.editAvailable = this.allStudents.filter(s => !studentIds.includes(s.id) && s.clubId == this.editSelectedClubId);
                    } else {
                        this.editAvailable = this.allStudents.filter(s => !studentIds.includes(s.id));
                    }
                    
                    this.submitting = false;
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-tournament' }));
                }
            }
        }
    </script>
</x-app-layout>
