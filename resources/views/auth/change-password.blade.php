<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 font-bold bg-amber-50 p-4 rounded-lg border border-amber-200">
        {{ __('Por razones de seguridad, debes actualizar tu contraseña genérica antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.update.mandatory') }}">
        @csrf

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nueva Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Actualizar Contraseña') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-red-500 underline uppercase font-bold">
                {{ __('Cerrar Sesión') }}
            </button>
        </form>
    </div>
</x-guest-layout>
