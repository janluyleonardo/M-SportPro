<x-app-layout>
  <style>[x-cloak] { display: none !important; }</style>
  <x-slot name="header">
    <div class="flex items-center space-x-3">
      <a href="{{ route('students.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="bi bi-arrow-left-circle-fill text-2xl"></i>
      </a>
      <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
        {{ __('Editar Deportista:') }} {{ $student->nomDeportista }}
      </h2>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
        
        <form action="{{ route('students.update', $student) }}" method="post" enctype="multipart/form-data" class="p-6 sm:p-8" x-data="{ 
            activeTab: 'athlete',
            submitting: false
        }" @submit="submitting = true">
          @csrf
          @method('PATCH')

          <!-- Progress/Tabs Bar -->
          <div class="flex border-b border-gray-200 mb-8 overflow-x-auto hide-scrollbar">
            <button type="button" @click="activeTab = 'athlete'" :class="{'border-blue-500 text-blue-600': activeTab === 'athlete', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'athlete'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-semibold text-sm transition-colors">
              <i class="bi bi-person-badge mr-2"></i> {{ __('Athlete Info') }}
            </button>
            <button type="button" @click="activeTab = 'mother'" :class="{'border-blue-500 text-blue-600': activeTab === 'mother', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'mother'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-semibold text-sm transition-colors">
              <i class="bi bi-person-hearts mr-2"></i> {{ __('Mother Info') }}
            </button>
            <button type="button" @click="activeTab = 'father'" :class="{'border-blue-500 text-blue-600': activeTab === 'father', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'father'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-semibold text-sm transition-colors">
              <i class="bi bi-person-fill mr-2"></i> {{ __('Father Info') }}
            </button>
            <button type="button" @click="activeTab = 'medical'" :class="{'border-blue-500 text-blue-600': activeTab === 'medical', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'medical'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-semibold text-sm transition-colors">
              <i class="bi bi-clipboard2-pulse mr-2"></i> {{ __('Medical History') }}
            </button>
          </div>

          <!-- Tab Content: Athlete Information -->
          <div x-show="activeTab === 'athlete'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Información Principal del Deportista</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              
              <!-- Foto -->
              <div class="lg:col-span-3" x-data="{ 
                  preview: '{{ $student->Photo ? asset($student->Photo) : '' }}',
                  fileName: '',
                  isNew: false
              }">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto del Deportista</label>
                
                <!-- Estado: Sin imagen -->
                <div x-show="!preview || preview === ''" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-club-primary/50 transition-colors bg-gray-50 cursor-pointer" @click="$refs.photoInput.click()">
                  <div class="space-y-1 text-center">
                    <i class="bi bi-camera text-4xl text-gray-400"></i>
                    <div class="flex text-sm text-gray-600 justify-center">
                      <span class="font-medium text-club-primary hover:opacity-80">Seleccionar foto</span>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG hasta 2MB</p>
                  </div>
                </div>

                <!-- Estado: Con imagen (actual o nueva) -->
                <div x-show="preview && preview !== ''" x-cloak class="mt-1 flex items-center space-x-4 p-4 border-2 border-dashed rounded-xl"
                     :class="isNew ? 'border-green-300 bg-green-50/50' : 'border-gray-300 bg-gray-50'">
                  <div class="h-20 w-20 rounded-2xl overflow-hidden border-2 border-white shadow-lg flex-shrink-0">
                    <img :src="preview" class="h-full w-full object-cover" alt="Preview">
                  </div>
                  <div class="flex-1 min-w-0">
                    <template x-if="isNew">
                      <div>
                        <p class="text-sm font-bold text-green-700 flex items-center">
                          <i class="bi bi-check-circle-fill mr-1.5"></i> Nueva imagen seleccionada
                        </p>
                        <p class="text-xs text-gray-500 truncate mt-0.5" x-text="fileName"></p>
                      </div>
                    </template>
                    <template x-if="!isNew">
                      <p class="text-sm font-semibold text-gray-600 flex items-center">
                        <i class="bi bi-image mr-1.5"></i> Foto actual
                      </p>
                    </template>
                    <button type="button" @click="$refs.photoInput.click()" class="mt-2 text-xs font-semibold text-club-primary hover:underline">
                      <i class="bi bi-arrow-repeat mr-1"></i> <span x-text="isNew ? 'Elegir otra' : 'Cambiar foto'"></span>
                    </button>
                  </div>
                </div>

                <input x-ref="photoInput" name="Photo" type="file" accept="image/png, image/jpeg" class="hidden" 
                       @change="
                          const file = $event.target.files[0];
                          if (file) {
                              fileName = file.name;
                              isNew = true;
                              const reader = new FileReader();
                              reader.onload = (e) => { preview = e.target.result; };
                              reader.readAsDataURL(file);
                          }
                       ">
                <p class="text-xs text-gray-400 mt-1.5 italic"><i class="bi bi-info-circle mr-0.5"></i> Dejar vacío si no desea cambiar la foto.</p>
              </div>

              @if(auth()->user()->is_super_admin && $clubs->isNotEmpty())
              <!-- Club Selection (Solo Super Admin) -->
              <div class="lg:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Club <span class="text-red-500">*</span></label>
                <select name="club_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-club-primary focus:ring focus:ring-club-primary/20 transition-all text-sm">
                  <option value="">Seleccione el Club...</option>
                  @foreach($clubs as $club)
                    <option value="{{ $club->id }}" {{ old('club_id', $student->club_id) == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                  @endforeach
                </select>
              </div>
              @endif

              <!-- Nombres -->
              <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Full Name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="nomDeportista" value="{{ old('nomDeportista', $student->nomDeportista) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <!-- Documento -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">N° Documento <span class="text-red-500">*</span></label>
                <input type="number" name="numDocumento" value="{{ old('numDocumento', $student->numDocumento) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <!-- Fechas -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                <input type="date" name="fechaNacimiento" value="{{ old('fechaNacimiento', $student->fechaNacimiento) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Inscripción <span class="text-red-500">*</span></label>
                <input type="date" name="fechaInscripcion" value="{{ old('fechaInscripcion', $student->fechaInscripcion) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              
              <!-- Categoria -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                <select name="Categoria" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-sm">
                  <option value="">Seleccione...</option>
                  <option value="Mayores" {{ old('Categoria', $student->Categoria) == 'Mayores' ? 'selected' : '' }}>Mayores (18+ años)</option>
                  @php $currentYear = date('Y'); @endphp
                  @for ($year = ($currentYear - 5); $year >= ($currentYear - 17); $year--)
                    <option value="{{ $year }}" {{ old('Categoria', $student->Categoria) == $year ? 'selected' : '' }}>Categoría {{ $year }}</option>
                  @endfor
                </select>
              </div>

              <!-- Género -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Género <span class="text-red-500">*</span></label>
                <div class="flex space-x-4">
                  <label class="flex items-center space-x-2 cursor-pointer p-2 border border-gray-200 rounded-lg hover:bg-gray-50 flex-1 justify-center">
                    <input type="radio" name="genero" value="Masculino" {{ old('genero', $student->genero) == 'Masculino' ? 'checked' : '' }} required class="text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-700"><i class="bi bi-gender-male text-blue-500 mr-1"></i> Masculino</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer p-2 border border-gray-200 rounded-lg hover:bg-gray-50 flex-1 justify-center">
                    <input type="radio" name="genero" value="Femenino" {{ old('genero', $student->genero) == 'Femenino' ? 'checked' : '' }} required class="text-pink-600 focus:ring-pink-500 border-gray-300">
                    <span class="text-sm font-medium text-gray-700"><i class="bi bi-gender-female text-pink-500 mr-1"></i> Femenino</span>
                  </label>
                </div>
              </div>

              <!-- Físico -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Peso (kg) <span class="text-red-500">*</span></label>
                <input type="text" name="PesoDeportista" value="{{ old('PesoDeportista', $student->PesoDeportista) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Estatura (cm) & RH <span class="text-red-500">*</span></label>
                <div class="flex space-x-2">
                  <input type="text" name="EstaturaDeportista" value="{{ old('EstaturaDeportista', $student->EstaturaDeportista) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                  <input type="text" name="RHDeportista" value="{{ old('RHDeportista', $student->RHDeportista) }}" placeholder="RH" required class="block w-24 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                </div>
              </div>

              <!-- Ubicación -->
              <div class="lg:col-span-3">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 mt-4">Ubicación y Contacto</h4>
              </div>

              <div class="lg:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                <input type="text" name="direccionDeportista" value="{{ old('direccionDeportista', $student->direccionDeportista) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Barrio <span class="text-red-500">*</span></label>
                <input type="text" name="barrio" value="{{ old('barrio', $student->barrio) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Localidad <span class="text-red-500">*</span></label>
                <input type="text" name="localidad" value="{{ old('localidad', $student->localidad) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ciudad <span class="text-red-500">*</span></label>
                <input type="text" name="Ciudad" value="{{ old('Ciudad', $student->Ciudad) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono Principal <span class="text-red-500">*</span></label>
                <input type="number" name="numTelefonico" value="{{ old('numTelefonico', $student->numTelefonico) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono Opcional 1</label>
                <input type="number" name="numTelefonicoUno" value="{{ old('numTelefonicoUno', $student->numTelefonicoUno) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono Opcional 2</label>
                <input type="number" name="numTelefonicoDos" value="{{ old('numTelefonicoDos', $student->numTelefonicoDos) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <!-- Otros -->
              <div class="lg:col-span-3">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 mt-4">Académico & Salud</h4>
              </div>

              <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Colegio <span class="text-red-500">*</span></label>
                <input type="text" name="Colegio" value="{{ old('Colegio', $student->Colegio) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Curso <span class="text-red-500">*</span></label>
                <input type="number" name="Curso" value="{{ old('Curso', $student->Curso) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departamento</label>
                <input type="text" name="Departamento" value="{{ old('Departamento', $student->Departamento) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">EPS <span class="text-red-500">*</span></label>
                <input type="text" name="EPS" value="{{ old('EPS', $student->EPS) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

            </div>
            
            <div class="mt-8 flex justify-end">
              <button type="button" @click="activeTab = 'mother'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-lg font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                Siguiente: Info Madre <i class="bi bi-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Tab Content: Mother Information -->
          <div x-show="activeTab === 'mother'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Información de la Madre</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                <input type="text" name="nombreMama" value="{{ old('nombreMama', $student->nombreMama) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nº Documento <span class="text-red-500">*</span></label>
                <input type="number" name="documentoMama" value="{{ old('documentoMama', $student->documentoMama) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                <input type="number" name="telefonoMama" value="{{ old('telefonoMama', $student->telefonoMama) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition-all">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                <input type="email" name="correoMama" value="{{ old('correoMama', $student->correoMama) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition-all">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                <input type="text" name="direccionMama" value="{{ old('direccionMama', $student->direccionMama) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition-all">
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button type="button" @click="activeTab = 'athlete'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i> Atrás
              </button>
              <button type="button" @click="activeTab = 'father'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-lg font-semibold text-white hover:bg-gray-700 transition-colors">
                Siguiente: Info Padre <i class="bi bi-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Tab Content: Father Information -->
          <div x-show="activeTab === 'father'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Información del Padre</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                <input type="text" name="nombrePapa" value="{{ old('nombrePapa', $student->nombrePapa) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nº Documento <span class="text-red-500">*</span></label>
                <input type="number" name="documentoPapa" value="{{ old('documentoPapa', $student->documentoPapa) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                <input type="number" name="telefonoPapa" value="{{ old('telefonoPapa', $student->telefonoPapa) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                <input type="email" name="correoPapa" value="{{ old('correoPapa', $student->correoPapa) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                <input type="text" name="direccionPapa" value="{{ old('direccionPapa', $student->direccionPapa) }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button type="button" @click="activeTab = 'mother'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i> Atrás
              </button>
              <button type="button" @click="activeTab = 'medical'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-lg font-semibold text-white hover:bg-gray-700 transition-colors">
                Siguiente: Historial Médico <i class="bi bi-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Tab Content: Medical History -->
          <div x-show="activeTab === 'medical'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Historial Médico del Deportista</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-red-50 p-6 rounded-xl border border-red-100">
              
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-800 mb-1">Enfermedades que padece <span class="text-red-500">*</span></label>
                <input type="text" name="enfermedades" value="{{ old('enfermedades', $student->enfermedades) }}" placeholder="Ej: Asma, Ninguna..." required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>
              
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-800 mb-1">Medicamentos actuales <span class="text-red-500">*</span></label>
                <input type="text" name="medicamento" value="{{ old('medicamento', $student->medicamento) }}" placeholder="Ej: Inhalador, Ninguno..." required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1">¿Lesiones congénitas? <span class="text-red-500">*</span></label>
                <input type="text" name="lesion" value="{{ old('lesion', $student->lesion) }}" placeholder="Ej: Sí (Especificar), No" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>
              
              <div>
                <label class="block text-sm font-semibold text-gray-800 mb-1">¿Cirugías previas? <span class="text-red-500">*</span></label>
                <input type="text" name="Cirugia" value="{{ old('Cirugia', $student->Cirugia) }}" placeholder="Ej: Apendicitis, No" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>
              
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-800 mb-1">¿Impedimentos físicos para el deporte? <span class="text-red-500">*</span></label>
                <input type="text" name="impedimento" value="{{ old('impedimento', $student->impedimento) }}" placeholder="Ej: Ninguno" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>
              
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-800 mb-1">¿Lesiones óseo musculares? <span class="text-red-500">*</span></label>
                <input type="text" name="lesionOM" value="{{ old('lesionOM', $student->lesionOM) }}" placeholder="Ej: Esguince tobillo derecho, Ninguna" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 transition-all">
              </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center bg-gray-50 -mx-6 -mb-6 p-6 rounded-b-2xl">
              <button type="button" @click="activeTab = 'father'; window.scrollTo(0,0);" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i> Atrás
              </button>
              <button type="submit" 
                      :disabled="submitting"
                      :class="submitting ? 'opacity-75 cursor-not-allowed' : 'hover:scale-105'"
                      class="inline-flex items-center px-8 py-4 bg-gray-800 border border-transparent rounded-xl font-bold text-lg text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all shadow-lg">
                <template x-if="!submitting">
                    <i class="bi bi-save2-fill mr-2 text-2xl"></i>
                </template>
                <template x-if="submitting">
                    <svg class="animate-spin -ml-1 mr-2 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="submitting ? 'Guardando...' : '{{__('Guardar Cambios')}}'"></span>
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</x-app-layout>
