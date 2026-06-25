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
      <div class="mb-6 flex flex-col lg:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-3 w-full lg:w-auto">
          <div class="p-3 bg-blue-50 text-club-primary rounded-lg">
            <i class="bi bi-people-fill text-xl"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">{{ __('Total Athletes') }}</p>
            <p class="text-2xl font-bold text-gray-900 leading-none">{{ $studentsCount }}</p>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="w-full lg:flex-1 lg:max-w-md">
            <form action="{{ route('students.index') }}" method="GET" class="relative group">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Buscar por nombre, categoría o documento..." 
                       class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-club-primary focus:border-club-primary transition-all duration-200 group-hover:bg-white"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-hover:text-club-primary transition-colors duration-200"></i>
                </div>
                @if($search)
                    <a href="{{ route('students.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors" title="Limpiar búsqueda">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-2" x-data="{ exporting: false, showImport: false }">
          @role('Admin')
          <!-- Botón Exportar -->
          <a href="#" data-no-loader="true"
             @click.prevent="if (!exporting) { exporting = true; window.location.href = '{{ route('export') }}'; setTimeout(() => exporting = false, 3000); }"
             :class="exporting ? 'opacity-75 cursor-not-allowed pointer-events-none' : ''"
             class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-emerald-600 transition-colors duration-200 shadow-sm">
            <template x-if="!exporting">
                <i class="bi bi-file-earmark-excel-fill mr-2"></i>
            </template>
            <template x-if="exporting">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <span x-text="exporting ? 'Generando...' : '{{ __('Export Directory') }}'"></span>
          </a>

          <!-- Botón Importar -->
          <button @click="showImport = true" class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-indigo-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-600 transition-colors duration-200 shadow-sm">
            <i class="bi bi-file-earmark-arrow-up-fill mr-2 text-lg"></i> {{ __('Import') }}
          </button>

          <!-- Modal de Importación -->
          <div x-show="showImport" 
               x-cloak 
               class="fixed inset-0 z-50 overflow-y-auto" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="opacity-100"
               x-transition:leave-end="opacity-0">
              <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showImport = false"></div>
                  <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                  
                  <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                      <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="p-8" x-data="{ importing: false, fileName: '' }" @submit="importing = true">
                          @csrf
                          <div class="flex items-center justify-between mb-6">
                              <h3 class="text-xl font-black text-gray-900 flex items-center">
                                <i class="bi bi-cloud-arrow-up-fill mr-2 text-indigo-500"></i>
                                Importar Deportistas
                              </h3>
                              <button type="button" @click="showImport = false" class="text-gray-400 hover:text-gray-600 transition-colors" :disabled="importing">
                                  <i class="bi bi-x-lg"></i>
                              </button>
                          </div>
                          
                          <div class="space-y-6">
                              <p class="text-sm text-gray-500 leading-relaxed">Sube un archivo Excel (.xlsx, .xls) o CSV con la información de los deportistas para cargarlos masivamente al sistema.</p>
                              
                              <div class="relative group">
                                  <label for="file-upload" 
                                         :class="importing ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-400'"
                                         class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 transition-all duration-300">
                                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                          <i class="bi bi-file-earmark-spreadsheet text-3xl text-gray-400 group-hover:text-indigo-500 group-hover:scale-110 transition-all duration-300 mb-2"></i>
                                          <p class="text-xs font-bold text-gray-500 group-hover:text-indigo-600 uppercase tracking-wider">Haga clic o arrastre su archivo</p>
                                          <p class="text-[10px] text-gray-400 mt-1">XLSX, XLS, CSV (Máx. 10MB)</p>
                                      </div>
                                      <input id="file-upload" name="file" type="file" class="hidden" required accept=".xlsx,.xls,.csv" @change="fileName = $event.target.files[0].name">
                                  </label>
                                  <template x-if="fileName">
                                      <div class="mt-2 text-xs font-bold text-indigo-600 flex items-center justify-center">
                                          <i class="bi bi-check-circle-fill mr-1"></i> Seleccionado: <span x-text="fileName" class="ml-1"></span>
                                      </div>
                                  </template>
                              </div>
                              
                              <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                  <h4 class="text-xs font-black text-blue-700 uppercase tracking-widest mb-2 flex items-center justify-between">
                                      <span class="flex items-center">
                                          <i class="bi bi-info-circle-fill mr-1.5"></i> Sugerencia de Formato
                                      </span>
                                      <a href="#" 
                                         data-no-loader="true"
                                         x-data="{ downloading: false }"
                                         @click.prevent="if (!downloading) { downloading = true; window.location.href = '{{ route('export.template') }}'; setTimeout(() => downloading = false, 3000); }"
                                         :class="downloading ? 'opacity-50 pointer-events-none' : ''"
                                         class="text-indigo-600 hover:text-indigo-800 flex items-center font-bold transition-colors">
                                          <template x-if="!downloading">
                                              <i class="bi bi-download mr-1"></i>
                                          </template>
                                          <template x-if="downloading">
                                              <i class="bi bi-arrow-repeat animate-spin mr-1"></i>
                                          </template>
                                          <span x-text="downloading ? 'Generando...' : 'Descargar Plantilla'"></span>
                                      </a>
                                  </h4>
                                  <p class="text-[10px] text-blue-600/80 leading-normal">
                                      Para mejores resultados, use cabeceras como: <span class="font-bold">nombre_deportista, documento, categoria, genero, fecha_nacimiento, telefono</span>.
                                  </p>
                              </div>
                          </div>
                          
                          <div class="mt-8 flex flex-col sm:flex-row gap-3">
                              <button type="submit" :disabled="importing" class="flex-1 inline-flex justify-center items-center rounded-xl px-4 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                  <template x-if="!importing">
                                      <span>Iniciar Importación</span>
                                  </template>
                                  <template x-if="importing">
                                      <span class="flex items-center">
                                          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>
                                          Importando...
                                      </span>
                                  </template>
                              </button>
                              <button type="button" @click="showImport = false" :disabled="importing" class="flex-1 inline-flex justify-center items-center rounded-xl px-4 py-3 bg-gray-100 text-sm font-bold text-gray-600 hover:bg-gray-200 transition-all disabled:opacity-50">
                                  Cancelar
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          @endrole
        </div>
      </div>

      <!-- Table / Cards Container -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        
        <!-- Desktop Table (Visible from sm up) -->
        <div class="hidden sm:block overflow-x-auto">
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
                      {{ __($student->Categoria) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <a href="{{ route('imprimir', $student) }}" target="_blank" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors border border-blue-100" title="{{__('Print')}}">
                        <i class="bi bi-printer-fill"></i>
                      </a>
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
                    @include('students.partials.modals')
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

        <!-- Mobile Cards View (Visible only on mobile) -->
        <div class="sm:hidden divide-y divide-gray-100">
          @forelse ($students as $student)
            <div class="p-4 bg-white hover:bg-gray-50 transition-colors" x-data="{ showModal: false, deleteModal: false }">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                  <div class="h-12 w-12 flex-shrink-0">
                    @if($student->Photo)
                      <img class="h-12 w-12 rounded-xl object-cover border border-gray-200 shadow-sm" src="{{ asset($student->Photo) }}" alt="{{ $student->nomDeportista }}">
                    @else
                      <div class="h-12 w-12 rounded-xl bg-club-primary flex items-center justify-center text-white font-bold text-xl shadow-inner">
                        {{ substr($student->nomDeportista, 0, 1) }}
                      </div>
                    @endif
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-bold text-gray-900 leading-tight">{{ Str::title($student->nomDeportista) }}</div>
                    <div class="text-[10px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-md uppercase mt-1 inline-block">Categoría {{ __($student->Categoria) }}</div>
                  </div>
                </div>
                <div class="text-right">
                   <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Documento</div>
                   <div class="text-xs font-mono font-bold text-gray-600">{{ $student->numDocumento }}</div>
                </div>
              </div>

              <div class="flex items-center justify-between bg-gray-50 p-2 rounded-xl border border-gray-100">
                <div class="text-[10px] text-gray-500 flex items-center italic truncate max-w-[150px]">
                  <i class="bi bi-envelope-at mr-1.5 text-club-primary"></i>
                  {{ $student->correoMama ?? $student->correoPapa ?? 'Sin correo' }}
                </div>
                <div class="flex space-x-1.5">
                  <a href="{{ route('imprimir', $student) }}" target="_blank" class="w-8 h-8 flex items-center justify-center text-blue-600 bg-white hover:bg-blue-50 rounded-lg transition-colors shadow-sm border border-gray-100">
                    <i class="bi bi-printer-fill"></i>
                  </a>
                  <button @click="showModal = true" class="w-8 h-8 flex items-center justify-center text-club-primary bg-white hover:bg-blue-50 rounded-lg transition-colors shadow-sm border border-gray-100">
                    <i class="bi bi-eye-fill"></i>
                  </button>
                  <a href="{{ route('students.edit', $student) }}" class="w-8 h-8 flex items-center justify-center text-amber-600 bg-white hover:bg-amber-50 rounded-lg transition-colors shadow-sm border border-gray-100">
                    <i class="bi bi-pencil-fill"></i>
                  </a>
                  @role('Admin')
                    <button @click="deleteModal = true" class="w-8 h-8 flex items-center justify-center text-red-600 bg-white hover:bg-red-50 rounded-lg transition-colors shadow-sm border border-gray-100">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  @endrole
                </div>
              </div>
              @include('students.partials.modals')
            </div>
          @empty
            <div class="p-8 text-center text-gray-500 italic">No hay deportistas registrados.</div>
          @endforelse
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
