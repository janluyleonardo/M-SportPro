<x-app-layout>
    <div x-data="{ openImport: false, fileName: '' }">
        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-club-secondary/20 text-club-primary rounded-2xl">
                        <i class="bi bi-box-seam-fill text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-2xl text-gray-900 tracking-tight">
                            {{ __('Inventory Management') }}
                        </h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Control de Artículos y
                            Stock</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="window.dispatchEvent(new CustomEvent('open-import-modal'))"
                        class="px-5 py-3 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-100 transition-all flex items-center">
                        <i class="bi bi-cloud-upload-fill mr-2"></i> Importar
                    </button>
                    <a href="{{ route('products.create') }}"
                        class="px-5 py-3 bg-club-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:scale-[1.02] transition-all active:scale-95 flex items-center">
                        <i class="bi bi-plus-circle-fill mr-2"></i> Nuevo Producto
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th
                                        class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Producto</th>
                                    @if(auth()->user()->is_super_admin)
                                        <th
                                            class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Club</th>
                                    @endif
                                    <th
                                        class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Precio</th>
                                    <th
                                        class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Stock</th>
                                    <th
                                        class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($products as $p)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                                                    @if($p->image)
                                                        <img src="{{ asset('storage/' . $p->image) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <i class="bi bi-tag text-xl text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-gray-900">{{ $p->name }}</p>
                                                    <p
                                                        class="text-[10px] text-gray-400 font-bold uppercase truncate max-w-xs">
                                                        {{ $p->description }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        @if(auth()->user()->is_super_admin)
                                            <td class="px-8 py-5">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 shadow-sm">
                                                    {{ $p->club->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                        @endif
                                        <td class="px-8 py-5">
                                            <span
                                                class="text-sm font-black text-gray-900">${{ number_format($p->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-2 h-2 rounded-full {{ $p->stock > 5 ? 'bg-green-400' : ($p->stock > 0 ? 'bg-amber-400' : 'bg-red-400') }}">
                                                </div>
                                                <span
                                                    class="text-sm font-black {{ $p->stock <= 0 ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $p->stock }} uds.
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('products.edit', $p) }}"
                                                    class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $p) }}" method="POST"
                                                    onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar producto', '¿Estás seguro de eliminar el producto &quot;{{ $p->name }}&quot;? Esta acción no se puede deshacer.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-20 text-center">
                                            <i class="bi bi-box-seam text-5xl text-gray-200 mb-4 block"></i>
                                            <p class="text-gray-400 font-bold italic">No hay productos registrados en el
                                                inventario.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-8">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Importar Productos -->
        <div x-data="{ open: false, fileName: '' }" @open-import-modal.window="open = true" x-cloak x-show="open"
            class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300"
                    @click.away="open = false">
                    <div class="p-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white">
                                    <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Importar Productos</h3>
                            </div>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>

                        <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                            Sube un archivo Excel (.xlsx, .xls) o CSV con la información de los productos para cargarlos
                            masivamente al sistema.
                        </p>

                        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6" onsubmit="
                                var btn = this.querySelector('button[type=submit]');
                                btn.disabled = true;
                                btn.classList.add('opacity-75', 'cursor-not-allowed');
                                btn.innerHTML = '<svg class=\'animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg> Guardando...';
                            ">
                            @csrf

                            <div class="relative">
                                <label
                                    class="cursor-pointer flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50 hover:bg-white hover:border-indigo-400 transition-all group">
                                    <i
                                        class="bi bi-file-earmark-excel text-4xl text-gray-300 group-hover:text-indigo-500 mb-3"></i>
                                    <span
                                        class="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-indigo-600"
                                        x-text="fileName || 'HAGA CLIC O ARRASTRE SU ARCHIVO'"></span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase mt-1">XLSX, XLS, CSV (Máx.
                                        10MB)</span>
                                    <input type="file" name="file" class="hidden" required
                                        @change="fileName = $event.target.files[0].name">
                                </label>
                            </div>

                            <div class="bg-blue-50/50 border border-blue-100 rounded-[1.5rem] p-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-blue-700">
                                        <i class="bi bi-info-circle-fill"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Sugerencia de
                                            Formato</span>
                                    </div>
                                    <a href="#" data-no-loader="true" x-data="{ downloading: false }"
                                        @click.prevent="if (!downloading) { downloading = true; window.location.href = '{{ route('products.template') }}'; setTimeout(() => downloading = false, 3000); }"
                                        :class="downloading ? 'opacity-50 pointer-events-none' : ''"
                                        class="flex items-center gap-1.5 text-blue-700 hover:text-blue-900 transition-colors">
                                        <template x-if="!downloading">
                                            <i class="bi bi-download"></i>
                                        </template>
                                        <template x-if="downloading">
                                            <i class="bi bi-arrow-repeat animate-spin"></i>
                                        </template>
                                        <span class="text-[10px] font-black uppercase tracking-widest"
                                            x-text="downloading ? 'Generando...' : 'Descargar Plantilla'"></span>
                                    </a>
                                </div>
                                <p class="text-[10px] text-blue-600/80 leading-relaxed font-medium">
                                    Para mejores resultados, use cabeceras como: <span
                                        class="font-black text-blue-700">nombre, descripcion, precio, stock.</span>
                                </p>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button type="submit"
                                    class="flex-[1.5] py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all uppercase text-xs tracking-widest">
                                    Iniciar Importación
                                </button>
                                <button type="button" @click="open = false"
                                    class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>