<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
        <input type="text" name="title" id="title" value="{{ old('title', $service->title ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $service->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="icon" class="block text-sm font-medium text-gray-700">الأيقونة (اسم Heroicon)</label>
        <input type="text" name="icon" id="icon" value="{{ old('icon', $service->icon ?? '') }}" placeholder="heroicon-o-code-bracket"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('icon') border-red-500 @enderror">
        <p class="mt-1 text-sm text-gray-500">أدخل اسم الأيقونة، مثل: "heroicon-o-code-bracket", "heroicon-o-device-phone-mobile"</p>
        @error('icon')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700">الترتيب</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sort_order') border-red-500 @enderror">
            @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center pt-6">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $service->is_visible ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_visible" class="mr-2 block text-sm text-gray-700">مرئي</label>
        </div>
    </div>
</div>
