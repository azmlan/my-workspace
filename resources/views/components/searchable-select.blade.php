<div x-data="{
    query: '',
    open: false,
    selectedValue: @js($selected ?? ''),
    selectedLabel: '',
    options: @js($options),
    get filtered() {
        if (!this.query) return this.options;
        const q = this.query.toLowerCase();
        return this.options.filter(o => o.label.toLowerCase().includes(q));
    },
    select(option) {
        this.selectedValue = option.value;
        this.selectedLabel = option.label;
        this.query = option.label;
        this.open = false;
    },
    init() {
        const found = this.options.find(o => o.value === this.selectedValue);
        if (found) { this.selectedLabel = found.label; this.query = found.label; }
    }
}" class="relative" @click.outside="open = false; query = selectedLabel">
    <input type="text"
        x-model="query"
        @focus="open = true"
        @input="open = true; if (query !== selectedLabel) { selectedValue = ''; selectedLabel = ''; }"
        @keydown.escape="open = false; query = selectedLabel"
        placeholder="{{ $placeholder ?? 'اختر...' }}"
        autocomplete="off"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
    <input type="hidden" name="{{ $name }}" :value="selectedValue">

    <div x-show="open" x-cloak
        class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto">
        <template x-for="option in filtered" :key="option.value">
            <div @click="select(option)"
                :class="selectedValue === option.value ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'"
                class="px-4 py-2 text-sm cursor-pointer"
                x-text="option.label">
            </div>
        </template>
        <div x-show="filtered.length === 0" class="px-4 py-2 text-sm text-gray-400">لا توجد نتائج</div>
    </div>
</div>
