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
                    <div class="text-[10px] font-black bg-club-secondary text-gray-900 px-1.5 py-0.5 rounded-md uppercase mt-1 inline-block">Categoría {{ $student->Categoria }}</div>
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
