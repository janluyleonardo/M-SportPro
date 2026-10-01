<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if(auth()->user()->hasAnyRole(['Admin', 'SubAdmin']) && auth()->user()->club_id)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    @include('profile.partials.update-club-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Enlace de Registro del Club') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Comparte este enlace con tus clientes/jugadores para que se registren automáticamente en tu club sin tener que seleccionarlo manualmente.') }}
                            </p>
                        </header>

                        <div class="flex flex-col sm:flex-row gap-3 items-stretch max-w-xl">
                            <div class="relative flex-1">
                                <input type="text" readonly id="registration-link" 
                                    value="{{ URL::signedRoute('register', ['club_id' => auth()->user()->club_id]) }}" 
                                    class="border-gray-300 focus-border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full text-sm bg-gray-50 text-gray-600 py-2.5 px-3">
                            </div>
                            
                            <button type="button" onclick="copyRegistrationLink()" 
                                class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <i class="bi bi-clipboard mr-2 text-sm"></i> {{ __('Copiar Enlace') }}
                            </button>
                        </div>
                        
                        <p id="copy-message" class="text-sm text-green-600 font-semibold mt-2 hidden transition-all duration-300">
                            <i class="bi bi-check-circle-fill mr-1"></i> {{ __('¡Enlace copiado al portapapeles!') }}
                        </p>
                    </section>
                </div>
            </div>

            <script>
                function copyRegistrationLink() {
                    const linkInput = document.getElementById('registration-link');
                    linkInput.select();
                    linkInput.setSelectionRange(0, 99999);
                    
                    const showMessage = () => {
                        const msg = document.getElementById('copy-message');
                        msg.classList.remove('hidden');
                        setTimeout(() => {
                            msg.classList.add('hidden');
                        }, 3000);
                    };

                    if (navigator.clipboard && window.isSecureContext) {
                        // Utilizar API moderna si está disponible y el entorno es seguro (HTTPS o localhost)
                        navigator.clipboard.writeText(linkInput.value)
                            .then(showMessage)
                            .catch(err => {
                                console.error('Error al copiar con Clipboard API: ', err);
                                fallbackCopy(linkInput, showMessage);
                            });
                    } else {
                        // Fallback para entornos no seguros (HTTP en producción)
                        fallbackCopy(linkInput, showMessage);
                    }
                }

                function fallbackCopy(inputElement, callback) {
                    try {
                        inputElement.select();
                        inputElement.setSelectionRange(0, 99999);
                        const successful = document.execCommand('copy');
                        if (successful) {
                            callback();
                        } else {
                            console.error('No se pudo copiar el enlace.');
                        }
                    } catch (err) {
                        console.error('Error usando execCommand como fallback: ', err);
                    }
                }
            </script>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
