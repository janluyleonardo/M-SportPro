@props([
    'categories' => [],
    'name' => 'category',
    'value' => '',
    'placeholder' => 'Buscar o elegir categoría...',
    'label' => 'Categoría',
    'required' => false,
    'inputClass' => 'focus:ring-club-primary focus:border-club-primary',
    'hoverBgClass' => 'hover:bg-club-primary/10 hover:text-club-primary',
    'selectedBgClass' => 'bg-club-primary/10 text-club-primary',
    'modelName' => null,
])

<div
    x-data="{
        open: false,
        search: '{{ $value }}',
        selected: '{{ $value }}',
        categories: {{ json_encode($categories) }},
        get filtered() {
            if (!this.search) return this.categories;
            return this.categories.filter(c => c.toLowerCase().includes(this.search.toLowerCase()));
        },
        choose(val) {
            this.selected = val;
            this.search = val;
            this.open = false;
            @if($modelName)
                {{ $modelName }} = val;
            @endif
            $dispatch('category-selected', val);
        }
    }"
    @if($modelName)
        x-init="$watch('{{ $modelName }}', val => { search = val; selected = val; })"
    @endif
    class="relative"
    @click.outside="open = false"
>
    @if($label)
        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">{{ $label }}</label>
    @endif
    <div class="relative">
        <input
            type="text"
            x-model="search"
            @focus="open = true"
            @input="open = true; selected = ''; @if($modelName) {{ $modelName }} = search; @endif"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="w-full border-gray-100 bg-gray-50 rounded-2xl p-3 pr-10 font-bold text-gray-700 {{ $inputClass }}"
            {{ $required ? 'required' : '' }}
        >
        <button type="button" @click="open = !open" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-club-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" :value="search">
    
    <!-- Dropdown list -->
    <div
        x-show="open && filtered.length > 0"
        x-transition
        class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg max-h-48 overflow-y-auto"
        style="display: none;"
    >
        <template x-for="cat in filtered" :key="cat">
            <button
                type="button"
                @click="choose(cat)"
                class="w-full text-left px-4 py-2.5 text-sm font-bold text-gray-700 {{ $hoverBgClass }} transition-colors first:rounded-t-2xl last:rounded-b-2xl"
                :class="{'{{ $selectedBgClass }}': selected === cat}"
                x-text="cat"
            ></button>
        </template>
    </div>
    <!-- No results -->
    <div
        x-show="open && filtered.length === 0 && search.length > 0"
        class="absolute z-50 mt-1 w-full bg-white border border-gray-100 rounded-2xl shadow-lg px-4 py-3 text-xs text-gray-400 italic"
        style="display: none;"
    >
        No hay categorías que coincidan. Se usará "<span class="font-bold text-gray-600" x-text="search"></span>".
    </div>
</div>
