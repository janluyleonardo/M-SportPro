<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 rounded-lg text-club-primary">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                {{ __('Mensualidades por Estudiante') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search Bar -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('payments.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o documento del estudiante..." class="w-full pl-12 pr-4 py-3 border-gray-200 rounded-2xl focus:ring-club-primary focus:border-club-primary text-sm shadow-inner bg-gray-50/50">
                    <div class="absolute left-4 top-3.5 text-gray-400">
                        <i class="bi bi-search text-lg"></i>
                    </div>
                    <button type="submit" class="absolute right-2 top-2 px-6 py-1.5 bg-club-primary text-white rounded-xl text-xs font-bold hover:opacity-90 transition-all shadow-sm">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Student Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @forelse($students as $student)
                    <div 
                        onclick="window.location='{{ route('payments.show', $student->id) }}'"
                        class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer group"
                    >
                        <div class="h-28 gradient-club relative">
                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <!-- Avatar Overlap -->
                            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 h-24 w-24 rounded-3xl bg-white p-1.5 shadow-xl group-hover:rotate-3 transition-transform duration-500">
                                <div class="h-full w-full rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center text-blue-600 font-black text-2xl border border-gray-100 shadow-inner">
                                    @php
                                        // Validación robusta de imagen
                                        $hasPhoto = !empty($student->Photo) && (str_contains($student->Photo, '/') || str_contains($student->Photo, '.'));
                                    @endphp

                                    @if($hasPhoto)
                                        <img src="{{ asset($student->Photo) }}" class="h-full w-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @endif

                                    <div class="initials-avatar {{ $hasPhoto ? 'hidden' : 'flex' }} items-center justify-center">
                                        @php
                                            $names = explode(' ', trim($student->nomDeportista));
                                            $initials = substr($names[0], 0, 1) . (count($names) > 1 ? substr(end($names), 0, 1) : '');
                                        @endphp
                                        <span class="text-club-primary">
                                            {{ strtoupper($initials) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-14 pb-8 px-8 text-center">
                            <h3 class="text-lg font-black text-gray-900 line-clamp-1 mb-1 group-hover:text-green-600 transition-colors">{{ $student->nomDeportista }}</h3>
                            <p class="text-xs text-gray-400 font-bold mb-6 uppercase tracking-[0.2em]">{{ $student->Categoria }}</p>
                            
                            <div class="flex flex-col items-center justify-center space-y-2 mb-8">
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-500 rounded-full text-[10px] font-black border border-gray-100">
                                    ID: {{ $student->numDocumento }}
                                </span>
                                
                                <div class="flex items-center">
                                    @if($student->balance > 0)
                                        <span class="px-4 py-1.5 bg-red-50 text-red-600 rounded-full text-[10px] font-black border border-red-100 animate-pulse">
                                            Deuda: ${{ number_format($student->balance, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black border border-green-100">
                                            Al día
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="inline-flex items-center justify-center w-full py-3.5 bg-club-primary text-white rounded-2xl text-[10px] uppercase tracking-widest font-black group-hover:opacity-90 transition-all shadow-lg shadow-gray-200 group-hover:shadow-indigo-100">
                                <i class="bi bi-wallet2 mr-2"></i> 
                                {{ Auth::user()->hasRole('Admin') ? 'Gestionar Mensualidades' : 'Ver Historial de Pagos' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gray-50 text-gray-300 mb-6 border border-gray-100">
                            <i class="bi bi-person-x text-4xl"></i>
                        </div>
                        <p class="text-gray-400 font-bold tracking-wide italic">No se encontraron estudiantes registrados.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
