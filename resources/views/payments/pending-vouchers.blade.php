<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    Comprobantes pendientes de revisión
                </h2>
                <p class="text-sm text-gray-500 mt-1">Revisa los recibos enviados por padres y acudientes.</p>
            </div>
            <a href="{{ route('payments.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50">
                <i class="bi bi-arrow-left"></i>
                Volver a mensualidades
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse($payments as $payment)
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-black text-gray-900">
                                    {{ $payment->student->nomDeportista }}
                                </h3>
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-800 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    Pendiente
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Documento: {{ $payment->student->numDocumento }}
                                <span class="mx-1">·</span>
                                Categoría: {{ $payment->student->Categoria }}
                            </p>

                            <dl class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">
                                <div>
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Mes de pago</dt>
                                    <dd class="text-sm font-bold text-gray-800">{{ $payment->month }}/{{ $payment->year }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Monto</dt>
                                    <dd class="text-sm font-bold text-gray-800">${{ number_format($payment->amount, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Enviado</dt>
                                    <dd class="text-sm font-bold text-gray-800">{{ $payment->created_at?->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>

                            @if($payment->notes)
                                <p class="mt-4 text-sm text-gray-600"><span class="font-bold">Nota:</span> {{ $payment->notes }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row lg:flex-col gap-2 lg:w-48">
                            <a href="{{ asset($payment->voucher) }}" target="_blank" rel="noopener"
                                class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl text-sm font-bold hover:bg-indigo-100">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                                Ver comprobante
                            </a>
                            <a href="{{ route('payments.show', $payment->student) }}"
                                class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-sm font-bold hover:bg-gray-100">
                                <i class="bi bi-clock-history"></i>
                                Historial del deportista
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2 mt-5 pt-4 border-t border-gray-100">
                        <form action="{{ route('payments.verify', $payment) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-black hover:bg-green-700">
                                <i class="bi bi-check-lg"></i>
                                Aprobar pago
                            </button>
                        </form>

                        <form action="{{ route('payments.reject', $payment) }}" method="POST"
                            class="flex flex-col sm:flex-row gap-2">
                            @csrf
                            <input type="text" name="rejection_reason" required maxlength="255"
                                placeholder="Motivo del rechazo"
                                class="border-gray-200 rounded-xl text-sm min-w-56">
                            <button type="submit"
                                class="inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 border border-red-100 rounded-xl text-sm font-black hover:bg-red-100">
                                <i class="bi bi-x-lg"></i>
                                Rechazar
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="mx-auto h-14 w-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center">
                        <i class="bi bi-check2-circle text-3xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-black text-gray-900">No hay comprobantes pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">Los nuevos recibos enviados aparecerán aquí.</p>
                </div>
            @endforelse

            @if($payments->hasPages())
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
