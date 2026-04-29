<x-app-layout>
    <!-- Script de emergencia para Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payments.index') }}" class="p-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <h2 class="font-bold text-xl text-black">
                Historial de Pagos
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Perfil Simple -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between border-2 border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="h-20 w-20 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-300 overflow-hidden relative">
                         @php
                            $initials = substr($student->nomDeportista, 0, 1);
                         @endphp
                         
                         <span class="absolute text-2xl font-bold text-gray-400 uppercase">{{ $initials }}</span>

                         @if(!empty($student->Photo))
                            <img src="{{ asset($student->Photo) }}" 
                                 class="absolute inset-0 h-full w-full object-cover rounded-lg z-10" 
                                 onerror="this.style.display='none'">
                         @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-black uppercase mb-1">{{ $student->nomDeportista }}</h1>
                        <p class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full inline-block border border-blue-100 uppercase">
                            {{ $student->Categoria }}
                        </p>
                        <p class="text-xs font-bold text-gray-500 mt-1">DOCUMENTO: {{ $student->numDocumento }}</p>
                    </div>
                </div>

                <!-- Botón de Acción Directo (Solo Admin) -->
                @role('Admin')
                <button 
                    onclick="document.getElementById('modal-pago').style.display = 'block'"
                    class="px-8 py-4 bg-club-primary hover:opacity-90 text-white font-black rounded-2xl shadow-xl transform active:scale-95 transition-all text-sm uppercase tracking-widest"
                >
                    + Registrar Mensualidad
                </button>
                @endrole
            </div>

            <!-- Listado de Pagos -->
            <div class="mt-8 space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em] mb-4">Pagos Realizados</h3>
                
                @forelse($payments as $payment)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-50 text-club-primary rounded-lg">
                                <i class="bi bi-cash-coin text-xl"></i>
                            </div>
                            <div>
                                @php $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; @endphp
                                <h4 class="font-black text-black text-lg">{{ $meses[$payment->month - 1] }} {{ $payment->year }}</h4>
                                <p class="text-[10px] font-bold text-gray-400">PAGADO EL: {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-8">
                            <!-- Asistencias (Solo Admin puede incrementar) -->
                            <div class="text-center">
                                <div class="text-[10px] font-bold text-gray-400 mb-1">ASISTENCIAS</div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-black text-black">{{ $payment->classes_used }} / 8</span>
                                    @role('Admin')
                                    <form action="{{ route('payments.update', $payment) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="increment_classes" value="1">
                                        <button type="submit" class="w-6 h-6 flex items-center justify-center bg-gray-100 text-gray-400 rounded hover:bg-club-primary hover:text-white transition-all">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                    @endrole
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-[10px] font-bold text-gray-400">MONTO</div>
                                <div class="text-xl font-black text-black">${{ number_format($payment->amount, 0, ',', '.') }}</div>
                            </div>

                            <!-- Acciones de Gestión (Solo Admin) -->
                            @role('Admin')
                            <div class="flex items-center space-x-2 border-l pl-6 border-gray-100">
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                      onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar pago', '¿Estás seguro de eliminar este registro de pago?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                            @endrole
                        </div>
                    </div>
                @empty
                    <div class="p-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 text-center">
                        <p class="text-gray-400 font-bold">No hay pagos registrados para este estudiante.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @role('Admin')
    <!-- Modal Alternativo (Simple HTML) -->
    <div id="modal-pago" style="display:none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-black">Nuevo Pago</h2>
                        <button onclick="document.getElementById('modal-pago').style.display = 'none'" class="text-gray-400 hover:text-black">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('payments.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Mes</label>
                                <select name="month" class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                                    @php $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; @endphp
                                    @foreach($meses as $index => $mes)
                                        <option value="{{ $index + 1 }}" {{ date('n') == $index + 1 ? 'selected' : '' }}>{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">Año</label>
                                <select name="year" class="w-full border-gray-200 rounded-xl mt-1 p-3 font-bold text-black" required>
                                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Monto ($)</label>
                            <input type="number" name="amount" value="50000" class="w-full border-gray-200 rounded-xl mt-1 p-4 font-black text-xl text-black" required>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="document.getElementById('modal-pago').style.display = 'none'" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl">
                                Cerrar
                            </button>
                            <button type="submit" class="flex-[2] py-4 bg-club-primary text-white font-black rounded-2xl shadow-lg shadow-blue-200">
                                Guardar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endrole
</x-app-layout>
