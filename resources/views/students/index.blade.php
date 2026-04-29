<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
        {{ __('Athletes Directory') }}
      </h2>
      <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-club-primary border border-transparent rounded-lg font-semibold text-sm text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-club-primary focus:ring-offset-2 transition ease-in-out duration-200 shadow-md hover:shadow-lg">
        <i class="bi bi-person-plus-fill mr-2 text-lg"></i> {{ __('Add Athlete') }}
      </a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      
      <!-- Stats / Actions Row -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="p-3 bg-blue-50 text-club-primary">
            <i class="bi bi-people-fill text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">{{ __('Total Athletes') }}</p>
            <p class="text-2xl font-bold text-gray-900 leading-none">{{ count($studentsCount) }}</p>
          </div>
        </div>
        <div>
          <a href="{{ route('export') }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-emerald-600 transition-colors duration-200 shadow-sm">
            <i class="bi bi-file-earmark-excel-fill mr-2"></i> {{ __('Export Directory') }}
          </a>
        </div>
      </div>

      <!-- Table Card -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/80">
              <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('DocumentNumber') }}</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              @forelse ($students as $index => $student)
                <tr class="hover:bg-blue-50/30 transition-colors duration-200" x-data="{ showModal: false, deleteModal: false }">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                    {{ $students->firstItem() + $index }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex-shrink-0">
                        @if($student->Photo)
                          <img class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm" src="{{ asset($student->Photo) }}" alt="{{ $student->nomDeportista }}">
                        @else
                          <div class="h-10 w-10 rounded-full bg-club-primary flex items-center justify-center text-white font-bold text-lg shadow-inner">
                            {{ substr($student->nomDeportista, 0, 1) }}
                          </div>
                        @endif
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900">{{ Str::title($student->nomDeportista) }}</div>
                        <div class="text-xs text-gray-500">{{ $student->correoMama ?? $student->correoPapa ?? 'Sin correo' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $student->numDocumento }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-club-secondary text-gray-900 border border-club-secondary/30">
                      {{ $student->Categoria }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button @click="showModal = true" class="text-club-primary hover:text-club-primary/80 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors border border-blue-100" title="{{__('See')}}">
                        <i class="bi bi-eye-fill"></i>
                      </button>
                      <a href="{{ route('students.edit', $student) }}" class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition-colors border border-amber-100" title="{{__('Edit')}}">
                        <i class="bi bi-pencil-fill"></i>
                      </a>
                      @role('Admin')
                        <button @click="deleteModal = true" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors border border-red-100" title="{{__('Delete')}}">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      @endrole
                    </div>

                    <!-- Alpine View Modal -->
                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                          <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
                            <div class="sm:flex sm:items-start">
                              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                                  <h3 class="text-2xl font-bold text-gray-900" id="modal-title">
                                    {{ Str::upper($student->nomDeportista) }}
                                  </h3>
                                  <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                    <i class="bi bi-x-lg"></i>
                                  </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                  <!-- Athlete Info -->
                                  <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 h-full">
                                    <h4 class="font-bold text-club-primary mb-4 uppercase text-xs tracking-wider flex items-center">
                                        <i class="bi bi-person-badge mr-2"></i> Información Personal
                                    </h4>
                                    @if($student->Photo)
                                      <div class="mb-5 flex justify-center">
                                        <img src="{{ asset($student->Photo) }}" class="h-32 w-32 rounded-2xl object-cover shadow-lg border-2 border-white ring-1 ring-gray-200" alt="{{ $student->nomDeportista }}">
                                      </div>
                                    @endif
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-gray-700">
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Categoría</span> <span class="font-semibold text-sm break-all">{{ $student->Categoria }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Documento</span> <span class="font-semibold text-sm break-all">{{ $student->numDocumento }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Nacimiento</span> <span class="font-semibold text-sm break-all">{{ $student->fechaNacimiento }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Género</span> <span class="font-semibold text-sm break-all">{{ $student->genero }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">RH</span> <span class="font-semibold text-sm break-all">{{ $student->RHDeportista }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Peso</span> <span class="font-semibold text-sm break-all">{{ $student->PesoDeportista }} kg</span></div>
                                      <div class="col-span-2"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Estatura</span> <span class="font-semibold text-sm break-all">{{ $student->EstaturaDeportista }} cm</span></div>
                                    </div>
                                  </div>
                                  
                                  <!-- Contact Info -->
                                  <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 h-full">
                                    <h4 class="font-bold text-club-primary mb-4 uppercase text-xs tracking-wider flex items-center">
                                        <i class="bi bi-geo-alt-fill mr-2"></i> Contacto & Ubicación
                                    </h4>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-gray-700">
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Ciudad</span> <span class="font-semibold text-sm break-all">{{ $student->Ciudad }}</span></div>
                                      <div class="border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Teléfono</span> <span class="font-semibold text-sm break-all">{{ $student->numTelefonico }}</span></div>
                                      <div class="col-span-2 border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Localidad</span> <span class="font-semibold text-sm break-words whitespace-normal">{{ $student->localidad }}</span></div>
                                      <div class="col-span-2 border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Barrio</span> <span class="font-semibold text-sm break-words whitespace-normal">{{ $student->barrio }}</span></div>
                                      <div class="col-span-2 border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Dirección</span> <span class="font-semibold text-sm break-words whitespace-normal">{{ $student->direccionDeportista }}</span></div>
                                      <div class="col-span-2 border-b border-gray-200/50 pb-1"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">Colegio</span> <span class="font-semibold text-sm break-words whitespace-normal">{{ $student->Colegio }}</span></div>
                                      <div class="col-span-2"><span class="block text-[10px] font-bold text-gray-400 uppercase tracking-tight">EPS</span> <span class="font-semibold text-sm break-words whitespace-normal">{{ $student->EPS }}</span></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    @role('Admin')
                      <!-- Alpine Delete Modal -->
                      <div x-show="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                          <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="deleteModal = false" aria-hidden="true"></div>
                          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                          <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                              <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                  <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                  <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Eliminar Deportista
                                  </h3>
                                  <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                      ¿Estás seguro de eliminar el registro de <strong class="text-gray-900">{{ Str::upper($student->nomDeportista) }}</strong>? Esta acción no se puede deshacer.
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                              <form action="{{ route('students.destroy', $student) }}" method="post" class="inline-block w-full sm:w-auto">
                                @csrf
                                @method('delete')
                                <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                  Eliminar registro
                                </button>
                              </form>
                              <button type="button" @click="deleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancelar
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endrole
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                      <div class="bg-gray-100 rounded-full p-4 mb-4">
                        <i class="bi bi-inbox text-4xl text-gray-400"></i>
                      </div>
                      <h3 class="text-lg font-medium text-gray-900 mb-1">No hay deportistas</h3>
                      <p class="text-sm text-gray-500 max-w-sm mx-auto">{{ __('El estudiante que esta buscando no existe en la base de datos o aún no hay registros.') }}</p>
                      <a href="{{ route('students.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                        <i class="bi bi-plus-lg mr-2"></i> Crear primero
                      </a>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if ($students->hasPages())
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            {{ $students->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
