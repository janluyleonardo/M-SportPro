<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('treasury.salaries') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-arrow-left-circle-fill text-2xl"></i>
            </a>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                {{ __('Payment History') }}: {{ $teacher->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Perfil resumido del profesor -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 bg-club-primary rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg">
                        {{ substr($teacher->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $teacher->name }}</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Teacher / Coach') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Total Historic Paid') }}</p>
                    <p class="text-2xl font-black text-emerald-600">${{ number_format($payments->sum('amount'), 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="relative">
                <!-- Línea vertical central -->
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                <div class="space-y-8">
                    @forelse($payments as $payment)
                        <div class="relative pl-20">
                            <!-- Círculo de la línea de tiempo -->
                            <div class="absolute left-6 top-1.5 w-4 h-4 bg-white border-4 border-club-primary rounded-full z-10 shadow-[0_0_0_4px_rgba(1,142,203,0.1)]"></div>

                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-[10px] font-black bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ __('Successful Payment') }}</span>
                                            <span class="text-xs font-bold text-gray-400 italic">{{ \Carbon\Carbon::parse($payment->date)->translatedFormat('d M, Y') }}</span>
                                        </div>
                                        <h4 class="font-black text-gray-900 text-lg">{{ $payment->description }}</h4>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-2xl font-black text-gray-900">${{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        
                                        @if($payment->attachment)
                                            <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank" class="mt-2 inline-flex items-center text-[10px] font-black text-club-primary uppercase tracking-widest hover:underline">
                                                <i class="bi bi-file-earmark-text-fill mr-1.5 text-xs"></i>
                                                {{ __('View Payment Support') }}
                                            </a>
                                        @else
                                            <span class="mt-2 inline-flex items-center text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                                                <i class="bi bi-file-earmark-x mr-1.5 text-xs"></i>
                                                {{ __('No support uploaded') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-gray-300 ml-20">
                            <i class="bi bi-calendar-x text-5xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-500 font-bold">{{ __('No payment records found') }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8 ml-20">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
