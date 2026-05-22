<section class="space-y-6">
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="bi bi-shield-fill text-sm"></i>
            </span>
            <span>{{ __('Personalización del Club') }}</span>
        </h2>

        <p class="mt-1.5 text-xs text-gray-500">
            {{ __("Configura la identidad visual de tu club. El logotipo y nombre se reflejarán en la barra de navegación y en todos los reportes PDF generados en la plataforma.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.club.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <!-- Left: Current Logo Display & Preview -->
            <div class="flex flex-col items-center justify-center p-5 bg-gray-50 rounded-2xl border border-gray-100 text-center">
                <label class="block text-xs font-black text-gray-400 uppercase mb-3">{{ __('Logotipo Actual') }}</label>
                
                <div class="relative w-32 h-32 bg-white rounded-2xl border border-gray-200/60 shadow-sm flex items-center justify-center overflow-hidden group">
                    @if($user->club->logo && file_exists(public_path($user->club->logo)))
                        <img id="club-logo-preview" class="w-full h-full object-contain p-2" src="{{ asset($user->club->logo) }}" alt="Logo {{ $user->club->name }}">
                    @else
                        <div id="club-logo-placeholder" class="text-gray-300 flex flex-col items-center">
                            <i class="bi bi-image text-4xl mb-1.5"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Sin Logotipo</span>
                        </div>
                        <img id="club-logo-preview" class="w-full h-full object-contain p-2 hidden" alt="Vista previa del logo">
                    @endif
                </div>

                <p class="text-[10px] text-gray-400 mt-3.5 leading-relaxed">
                    Sugerido: Formato PNG transparente o SVG plano.
                </p>
            </div>

            <!-- Right: Inputs for Name and Logo File -->
            <div class="md:col-span-2 space-y-6">
                <!-- Club Name -->
                <div>
                    <x-input-label for="club_name" :value="__('Nombre de la Institución / Club')" class="text-xs font-black text-gray-400 uppercase mb-2" />
                    <x-text-input id="club_name" name="name" type="text" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 font-bold text-gray-700 focus:border-indigo-500 focus:ring-indigo-500" :value="old('name', $user->club->name)" required autofocus autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Logo File Upload -->
                <div>
                    <x-input-label for="club_logo" :value="__('Subir Nuevo Logotipo')" class="text-xs font-black text-gray-400 uppercase mb-2" />
                    
                    <div class="relative flex items-center justify-center w-full">
                        <label for="club_logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100/50 hover:border-indigo-400 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 mb-1"></i>
                                <p class="mb-1 text-xs text-gray-500 font-bold"><span class="text-indigo-600">Haz clic para cargar</span> o arrastra y suelta</p>
                                <p class="text-[10px] text-gray-400">PNG, JPG, JPEG, SVG o GIF (Max. 2MB)</p>
                            </div>
                            <input id="club_logo" name="logo" type="file" class="hidden" accept="image/*" onchange="previewClubLogo(this)" />
                        </label>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-4">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase px-5 py-3 rounded-xl transition-all shadow-sm">
                {{ __('Guardar Cambios') }}
            </x-primary-button>

            @if (session('status') === 'club-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs text-green-600 font-semibold flex items-center space-x-1"
                >
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ __('Configuración del club guardada.') }}</span>
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewClubLogo(input) {
            const preview = document.getElementById('club-logo-preview');
            const placeholder = document.getElementById('club-logo-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>
