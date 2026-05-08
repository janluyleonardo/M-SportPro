<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-arrow-left-circle-fill text-2xl"></i>
            </a>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                Editar Producto: {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Nombre del Producto</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-900 bg-gray-50 focus:bg-white transition-all" required>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Descripción</label>
                                <textarea name="description" rows="3" class="w-full border-gray-200 rounded-2xl p-4 text-xs font-bold text-gray-700 bg-gray-50 focus:bg-white transition-all">{{ $product->description }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Precio ($)</label>
                                    <input type="number" name="price" value="{{ (int)$product->price }}" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-900 bg-gray-50 focus:bg-white transition-all" required>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Stock Actual</label>
                                    <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border-gray-200 rounded-2xl p-4 text-sm font-black text-gray-900 bg-gray-50 focus:bg-white transition-all" required>
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Imagen del Producto</label>
                                <div class="flex items-center gap-4">
                                    @if($product->image)
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden border border-gray-100 shrink-0">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="relative group flex-1">
                                        <label class="cursor-pointer flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all">
                                            <i class="bi bi-cloud-arrow-up text-xl text-gray-400"></i>
                                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-1">Cambiar Foto</span>
                                            <input type="file" name="image" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-[2] py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-club-primary transition-all active:scale-95">
                            Actualizar Producto
                        </button>
                        <a href="{{ route('products.index') }}" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-xs uppercase tracking-widest text-center hover:bg-gray-200 transition-all">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
