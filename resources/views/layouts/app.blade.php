<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jackeline F.S.') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dynamic Club Colors -->
        <style>
            :root {
                --club-primary: rgb({{ env('CLUB_COLOR_PRIMARY_RGB', '0, 74, 173') }});
                --club-secondary: rgb({{ env('CLUB_COLOR_SECONDARY_RGB', '255, 222, 89') }});
            }
            .bg-club-primary { background-color: var(--club-primary); }
            .bg-club-secondary { background-color: var(--club-secondary); }
            .text-club-primary { color: var(--club-primary); }
            .border-club-primary { border-color: var(--club-primary); }
            .gradient-club { 
                background: linear-gradient(135deg, var(--club-primary) 0%, var(--club-secondary) 100%); 
            }

            /* Toast animations */
            @keyframes toast-in {
                from { opacity: 0; transform: translateX(100%) scale(0.95); }
                to   { opacity: 1; transform: translateX(0)    scale(1); }
            }
            @keyframes toast-out {
                from { opacity: 1; transform: translateX(0)    scale(1); max-height: 100px; margin-bottom: 0.75rem; }
                to   { opacity: 0; transform: translateX(100%) scale(0.95); max-height: 0;    margin-bottom: 0; }
            }
            @keyframes progress-bar {
                from { width: 100%; }
                to   { width: 0%; }
            }
            .toast-enter { animation: toast-in 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
            .toast-leave { animation: toast-out 0.3s ease-in forwards; }
            .toast-progress { animation: progress-bar 4s linear forwards; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- ── Toast Notification System ──────────────────────────────── -->
        <div
            id="toast-container"
            x-data="{
                toasts: [],
                add(toast) {
                    const id = Date.now();
                    this.toasts.push({ id, ...toast, leaving: false });
                    setTimeout(() => this.remove(id), 4000);
                },
                remove(id) {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) {
                        t.leaving = true;
                        setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                    }
                }
            }"
            x-init="
                @if(session('success'))
                    add({ type: 'success', message: @js(session('success')) });
                @endif
                @if(session('error'))
                    add({ type: 'error', message: @js(session('error')) });
                @endif
                @if(session('warning'))
                    add({ type: 'warning', message: @js(session('warning')) });
                @endif
                @if($errors->any())
                    add({ type: 'error', message: @js($errors->first()) });
                @endif
            "
            class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end space-y-3 pointer-events-none"
            style="min-width: 0;"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    :class="toast.leaving ? 'toast-leave' : 'toast-enter'"
                    class="pointer-events-auto w-80 rounded-2xl shadow-2xl overflow-hidden"
                    :style="
                        toast.type === 'success' ? 'background:#fff; border: 1.5px solid #bbf7d0;' :
                        toast.type === 'error'   ? 'background:#fff; border: 1.5px solid #fecaca;' :
                                                   'background:#fff; border: 1.5px solid #fde68a;'
                    "
                >
                    <div class="flex items-start p-4 gap-3">
                        <!-- Icono -->
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                             :class="
                                 toast.type === 'success' ? 'bg-green-100 text-green-600' :
                                 toast.type === 'error'   ? 'bg-red-100 text-red-500' :
                                                            'bg-yellow-100 text-yellow-600'
                             ">
                            <i class="text-base"
                               :class="
                                   toast.type === 'success' ? 'bi bi-check-circle-fill' :
                                   toast.type === 'error'   ? 'bi bi-x-circle-fill' :
                                                              'bi bi-exclamation-triangle-fill'
                               "></i>
                        </div>

                        <!-- Mensaje -->
                        <div class="flex-1 pt-0.5">
                            <p class="text-xs font-black uppercase tracking-widest mb-0.5"
                               :class="
                                   toast.type === 'success' ? 'text-green-700' :
                                   toast.type === 'error'   ? 'text-red-600' :
                                                              'text-yellow-700'
                               "
                               x-text="
                                   toast.type === 'success' ? '¡Éxito!' :
                                   toast.type === 'error'   ? 'Error' :
                                                              'Atención'
                               "></p>
                            <p class="text-sm font-medium text-gray-700 leading-snug" x-text="toast.message"></p>
                        </div>

                        <!-- Cerrar -->
                        <button @click="remove(toast.id)"
                                class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="h-1 w-full"
                         :class="
                             toast.type === 'success' ? 'bg-green-50' :
                             toast.type === 'error'   ? 'bg-red-50' :
                                                        'bg-yellow-50'
                         ">
                        <div class="h-full toast-progress rounded-full"
                             :class="
                                 toast.type === 'success' ? 'bg-green-400' :
                                 toast.type === 'error'   ? 'bg-red-400' :
                                                            'bg-yellow-400'
                             "></div>
                    </div>
                </div>
            </template>
        </div>
        <!-- ── Confirm Dialog (reemplaza confirm() nativo) ─────────── -->
        <div
            id="confirm-dialog"
            x-data="{
                show: false,
                title: '',
                message: '',
                type: 'danger',
                formToSubmit: null,
                callbackFn: null,
                open(opts) {
                    this.title   = opts.title   || '¿Estás seguro?';
                    this.message = opts.message  || 'Esta acción no se puede deshacer.';
                    this.type    = opts.type     || 'danger';
                    this.formToSubmit = opts.form || null;
                    this.callbackFn  = opts.callback || null;
                    this.show = true;
                },
                accept() {
                    this.show = false;
                    if (this.formToSubmit) {
                        this.formToSubmit.submit();
                    }
                    if (this.callbackFn) {
                        this.callbackFn();
                    }
                    window.dispatchEvent(new CustomEvent('confirm-action-accepted'));
                },
                cancel() {
                    this.show = false;
                    this.formToSubmit = null;
                    this.callbackFn = null;
                }
            }"
            x-show="show"
            x-cloak
            @confirm-action.window="open($event.detail)"
            class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div x-show="show"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                 @click="cancel()"></div>

            <!-- Dialog -->
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">

                <div class="p-8 text-center">
                    <!-- Icono -->
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl flex items-center justify-center"
                         :class="type === 'warning' ? 'bg-yellow-50' : 'bg-red-50'">
                        <i class="text-3xl"
                           :class="type === 'warning' ? 'bi bi-exclamation-triangle-fill text-yellow-500' : 'bi bi-trash-fill text-red-400'"></i>
                    </div>

                    <h3 class="text-lg font-black text-gray-900 mb-2" x-text="title"></h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-8" x-text="message"></p>

                    <div class="flex gap-3">
                        <button @click="cancel()"
                                class="flex-1 py-3.5 bg-gray-100 text-gray-600 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all">
                            Cancelar
                        </button>
                        <button @click="accept()"
                                class="flex-1 py-3.5 rounded-2xl font-black text-sm uppercase tracking-wider shadow-lg transition-all active:scale-95"
                                :class="type === 'warning' ? 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-yellow-100' : 'bg-red-500 hover:bg-red-600 text-white shadow-red-100'">
                            <i class="bi mr-1" :class="type === 'warning' ? 'bi-check-lg' : 'bi-trash-fill'"></i>
                            <span x-text="type === 'warning' ? 'Sí, continuar' : 'Eliminar'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ─────────────────────────────────────────────────────────── -->

        <script>
            function confirmAction(form, title, message, type) {
                window.dispatchEvent(new CustomEvent('confirm-action', {
                    detail: { form, title, message, type: type || 'danger' }
                }));
            }
        </script>
    </body>
</html>
