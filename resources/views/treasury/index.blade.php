<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-club-primary/10 rounded-2xl text-club-primary">
                    <i class="bi bi-bank2 text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-gray-900 tracking-tight">
                        Tesorería y Caja
                    </h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Control de Ingresos y Egresos</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('treasury.index') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
                    <select name="month" class="border-none focus:ring-0 text-xs font-bold text-gray-600 bg-transparent py-1">
                        @php $meses = [__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')]; @endphp
                        @foreach($meses as $idx => $m)
                            <option value="{{ $idx + 1 }}" {{ $month == ($idx + 1) ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    <select name="year" class="border-none focus:ring-0 text-xs font-bold text-gray-600 bg-transparent py-1">
                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="p-1.5 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="bi bi-filter text-gray-400"></i>
                    </button>
                </form>

                <button @click="$dispatch('open-transaction-modal')" class="px-5 py-3 bg-club-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:scale-[1.02] transition-all active:scale-95 flex items-center">
                    <i class="bi bi-plus-circle-fill mr-2"></i> Nuevo Registro
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        type: 'income',
        category: 'monthly_payment',
        amount: '',
        date: '{{ date('Y-m-d') }}'
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Resumen de Caja -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center">
                        <i class="bi bi-arrow-up-right text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Ingresos</p>
                        <p class="text-2xl font-black text-gray-900">${{ number_format($totalIncome, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                        <i class="bi bi-arrow-down-left text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Egresos</p>
                        <p class="text-2xl font-black text-gray-900">${{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="relative overflow-hidden p-6 rounded-[2rem] shadow-xl text-white flex items-center gap-5 {{ ($totalIncome - $totalExpense) >= 0 ? 'bg-indigo-600' : 'bg-red-600' }}">
                    <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12">
                        <i class="bi bi-safe2 text-9xl"></i>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <i class="bi bi-wallet2 text-2xl"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-1">Balance Neto</p>
                        <p class="text-2xl font-black">${{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Listado de Transacciones -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Movimientos de {{ $meses[$month-1] }}</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('treasury.index', ['month' => $month, 'year' => $year, 'type' => 'all']) }}" 
                           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all {{ request('type') == 'all' || !request('type') ? 'bg-gray-900 text-white shadow-lg' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">Todos</a>
                        <a href="{{ route('treasury.index', ['month' => $month, 'year' => $year, 'type' => 'income']) }}" 
                           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all {{ request('type') == 'income' ? 'bg-green-600 text-white shadow-lg' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">Ingresos</a>
                        <a href="{{ route('treasury.index', ['month' => $month, 'year' => $year, 'type' => 'expense']) }}" 
                           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all {{ request('type') == 'expense' ? 'bg-red-600 text-white shadow-lg' : 'bg-red-50 text-red-600 hover:bg-red-100' }}">Egresos</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Categoría</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Descripción</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-bold text-gray-900">{{ \Carbon\Carbon::parse($t->date)->format('d M, Y') }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full {{ $t->type == 'income' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-600">
                                                {{ __($t->category) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-medium text-gray-500">{{ $t->description }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span class="text-sm font-black {{ $t->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $t->type == 'income' ? '+' : '-' }} ${{ number_format($t->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <i class="bi bi-clipboard2-x text-5xl text-gray-200 mb-4 block"></i>
                                        <p class="text-gray-400 font-bold italic">No hay movimientos registrados este mes.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-8 bg-gray-50/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Registro -->
    <div id="modal-transaction" x-data="{ open: false, loading: false }"
        @open-transaction-modal.window="open = true"
        x-cloak x-show="open" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300"
                @click.away="open = false">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-black">Nuevo Registro</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Tesorería y Caja</p>
                        </div>
                        <button @click="open = false" class="text-gray-400 hover:text-black transition-colors">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('treasury.store') }}" method="POST" class="space-y-6" @submit="loading = true" x-data="{ type: 'income' }">
                        @csrf
                        
                        <div class="flex p-1 bg-gray-50 rounded-2xl border border-gray-100">
                            <button type="button" @click="type = 'income'" 
                                    class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                                    :class="type == 'income' ? 'bg-white text-green-600 shadow-sm border border-green-100' : 'text-gray-400'">
                                <i class="bi bi-plus-circle mr-2"></i> Ingreso
                            </button>
                            <button type="button" @click="type = 'expense'"
                                    class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                                    :class="type == 'expense' ? 'bg-white text-red-600 shadow-sm border border-red-100' : 'text-gray-400'">
                                <i class="bi bi-dash-circle mr-2"></i> Egreso
                            </button>
                            <input type="hidden" name="type" :value="type">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Categoría</label>
                                <select name="category" class="w-full border-gray-200 rounded-2xl p-4 text-xs font-black text-gray-700 bg-gray-50 focus:bg-white transition-all" required>
                                    <template x-if="type == 'income'">
                                        <optgroup label="Ingresos">
                                            <option value="monthly_payment">{{ __('monthly_payment') }}</option>
                                            <option value="sporting_goods">{{ __('sporting_goods') }}</option>
                                            <option value="other">{{ __('other') }}</option>
                                        </optgroup>
                                    </template>
                                    <template x-if="type == 'expense'">
                                        <optgroup label="Egresos">
                                            <option value="rent">{{ __('rent') }}</option>
                                            <option value="teacher_salary">{{ __('teacher_salary') }}</option>
                                            <option value="supplies">{{ __('supplies') }}</option>
                                            <option value="other">{{ __('other') }}</option>
                                        </optgroup>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Monto ($)</label>
                                <input type="number" name="amount" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-900 bg-gray-50 focus:bg-white transition-all" placeholder="Ej: 150000" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Fecha</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-2xl p-4 text-xs font-bold text-gray-700 bg-gray-50 focus:bg-white transition-all" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Referencia (Opcional)</label>
                                <input type="text" name="description" class="w-full border-gray-200 rounded-2xl p-4 text-xs font-bold text-gray-700 bg-gray-50 focus:bg-white transition-all" placeholder="Ej: Factura #123">
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" @click="open = false" :disabled="loading" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl disabled:opacity-50 transition-all hover:bg-gray-200 uppercase text-[10px] tracking-widest">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="loading" class="flex-[2] py-4 bg-club-primary text-white font-black rounded-2xl shadow-lg shadow-indigo-100 hover:scale-[1.02] transition-all disabled:opacity-50 flex items-center justify-center uppercase text-[10px] tracking-widest">
                                <span x-show="!loading">Guardar Registro</span>
                                <span x-show="loading" class="flex items-center">
                                    <i class="bi bi-arrow-repeat animate-spin mr-2 text-base"></i> Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
