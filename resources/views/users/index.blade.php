<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Gestión de Usuarios y Roles') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ openCreateModal: false, roleSelected: 'Padre' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Barra de Herramientas (Búsqueda y Nuevo) -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('users.index') }}" method="GET" class="w-full md:w-1/2 relative">
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, correo o rol..." 
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-club-primary focus:ring focus:ring-club-primary/20 transition-all text-sm">
                </form>

                <button @click="openCreateModal = true" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-club-primary text-white font-bold text-sm rounded-xl hover:opacity-90 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-person-plus-fill mr-2 text-lg"></i> {{ __('Nuevo Usuario Manual') }}
                </button>
            </div>

            <!-- Table / Cards Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                
                <!-- Desktop View (Table) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                @if(auth()->user()->is_super_admin)
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Club/Equipo</th>
                                @endif
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Vínculo Deportista</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cambiar Rol</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pago/Clase</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-club-primary font-bold border border-blue-100">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 leading-tight">{{ $user->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                                @foreach($user->roles as $role)
                                                    {{ $role->name }}@if(!$loop->last), @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                @if(auth()->user()->is_super_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="club_id" form="form-user-{{ $user->id }}" class="text-[10px] font-bold rounded-lg border-gray-200 focus:ring-club-primary focus:border-club-primary py-1 pl-2 pr-8 transition-all bg-gray-50">
                                            <option value="">-- Sin Club --</option>
                                            @foreach($clubs as $club)
                                                <option value="{{ $club->id }}" {{ $user->club_id == $club->id ? 'selected' : '' }}>
                                                    {{ $club->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('users.update', $user) }}" method="POST" id="form-user-{{ $user->id }}" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <div class="relative group">
                                            <i class="bi bi-person-badge absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                                            <input type="text" name="documento_deportista" value="{{ $user->documento_deportista }}" 
                                                   placeholder="N° Documento"
                                                   class="text-[10px] font-bold rounded-lg border-gray-200 focus:ring-club-primary focus:border-club-primary py-1 pl-7 pr-2 w-32 transition-all bg-gray-50"
                                                   title="Número de documento del deportista vinculado">
                                        </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="role" class="text-[10px] font-bold rounded-lg border-gray-200 focus:ring-club-primary focus:border-club-primary py-1 pl-2 pr-8 transition-all bg-gray-50">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="p-1.5 bg-club-primary text-white rounded-lg hover:opacity-90 transition-all shadow-sm">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]">$</span>
                                        <input type="number" name="pay_per_session" value="{{ (int)$user->pay_per_session }}" 
                                               form="form-user-{{ $user->id }}"
                                               {{ !$user->hasRole('Profesor') ? 'disabled' : '' }}
                                               class="text-[10px] font-bold rounded-lg border-gray-200 focus:ring-club-primary focus:border-club-primary py-1 pl-5 pr-1 w-20 transition-all {{ !$user->hasRole('Profesor') ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-transparent shadow-none' : 'bg-gray-50' }}">
                                    </div>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                              onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar usuario', '¿Estás seguro de eliminar a este usuario? Esta acción no se puede deshacer.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100">
                                                <i class="bi bi-trash-fill text-lg"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-black text-gray-400 bg-gray-50 px-3 py-1 rounded-full uppercase border border-gray-100 tracking-widest">Eres tú</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No se encontraron usuarios.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View (Cards) -->
                <div class="sm:hidden divide-y divide-gray-100">
                    @forelse($users as $user)
                        <div class="p-5 bg-white space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-club-primary font-black text-lg border border-blue-100 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 leading-tight">{{ $user->name }}</h4>
                                        <p class="text-[10px] font-bold text-gray-400 truncate max-w-[150px]">{{ $user->email }}</p>
                                    </div>
                                </div>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                          onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar usuario', '¿Estás seguro de eliminar a este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 text-red-500 bg-red-50 rounded-xl border border-red-100 transition-colors">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Rol Actual</p>
                                    @foreach($user->roles as $role)
                                        <span class="px-2.5 py-0.5 inline-flex text-[9px] font-black rounded-lg uppercase tracking-wider border bg-white shadow-sm
                                            @if($role->name == 'Admin') text-amber-600 border-amber-100
                                            @elseif($role->name == 'Profesor') text-club-primary border-blue-100
                                            @else text-gray-600 border-gray-200 @endif">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Registro</p>
                                    <p class="text-[10px] font-bold text-gray-600">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <form action="{{ route('users.update', $user) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                @method('PATCH')
                                @if(auth()->user()->is_super_admin)
                                    <div class="mb-2">
                                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Club / Equipo</label>
                                        <select name="club_id" class="w-full text-[11px] font-black rounded-xl border-gray-200 bg-white focus:ring-club-primary focus:border-club-primary py-2.5 pl-3 transition-all uppercase tracking-tighter">
                                            <option value="">-- Sin Club --</option>
                                            @foreach($clubs as $club)
                                                <option value="{{ $club->id }}" {{ $user->club_id == $club->id ? 'selected' : '' }}>
                                                    {{ $club->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="bi bi-person-badge absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                        <input type="text" name="documento_deportista" value="{{ $user->documento_deportista }}" 
                                               placeholder="N° Documento Deportista"
                                               class="w-full text-[11px] font-black rounded-xl border-gray-200 bg-white focus:ring-club-primary focus:border-club-primary py-2.5 pl-9 transition-all uppercase tracking-tighter">
                                    </div>
                                    <select name="role" class="flex-1 text-[11px] font-black rounded-xl border-gray-200 bg-white focus:ring-club-primary focus:border-club-primary py-2.5 pl-3 transition-all uppercase tracking-tighter">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                Rol: {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-club-primary text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 active:scale-95 transition-all">
                                    Actualizar Usuario
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400 italic">No hay usuarios registrados.</div>
                    @endforelse
                </div>
            </div>                
                <!-- Paginación -->
                @if($users->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        <!-- MODAL DE CREACIÓN -->
        <div x-show="openCreateModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Registrar Nuevo Usuario</h3>
                                <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                                        <input type="text" name="name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                                        <input type="email" name="email" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña Inicial</label>
                                        <input type="text" name="password" required value="{{ env('DEFAULT_USER_PASSWORD', 'password') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Rol del Usuario</label>
                                        <select name="role" x-model="roleSelected" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Vínculo Deportista (N° Documento)</label>
                                        <input type="text" name="documento_deportista" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all" placeholder="Documento niño">
                                    </div>
                                    <div class="col-span-1" x-show="roleSelected == 'Profesor'">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pago por Sesión ($)</label>
                                        <input type="number" name="pay_per_session" value="0" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>
                                @if(auth()->user()->is_super_admin)
                                    <div class="mt-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Club / Equipo</label>
                                        <select name="club_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                            <option value="">-- Sin Club --</option>
                                            @foreach($clubs as $club)
                                                <option value="{{ $club->id }}">
                                                    {{ $club->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <p class="mt-1 text-[10px] text-gray-400 italic text-center" x-show="roleSelected == 'Profesor'">Define el pago por clase solo si el usuario es Profesor.</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-club-primary text-base font-bold text-white hover:opacity-90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                                Crear Usuario
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
</x-app-layout>
