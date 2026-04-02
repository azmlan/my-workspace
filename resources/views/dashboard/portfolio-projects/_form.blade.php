<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
        <input type="text" name="title" id="title" value="{{ old('title', $project->title ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tech_tags" class="block text-sm font-medium text-gray-700">التقنيات (مفصولة بفواصل)</label>
        <input type="text" name="tech_tags" id="tech_tags" value="{{ old('tech_tags', isset($project) && $project->tech_tags ? implode(', ', $project->tech_tags) : '') }}" placeholder="Laravel, Vue.js, Tailwind CSS"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tech_tags') border-red-500 @enderror">
        @error('tech_tags')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="live_url" class="block text-sm font-medium text-gray-700">رابط المعاينة</label>
            <input type="url" name="live_url" id="live_url" value="{{ old('live_url', $project->live_url ?? '') }}" placeholder="https://example.com"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('live_url') border-red-500 @enderror">
            @error('live_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="github_url" class="block text-sm font-medium text-gray-700">رابط GitHub</label>
            <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('github_url') border-red-500 @enderror">
            @error('github_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700">الترتيب</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sort_order') border-red-500 @enderror">
            @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center pt-6">
            <input type="hidden" name="featured" value="0">
            <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $project->featured ?? false) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="featured" class="mr-2 block text-sm text-gray-700">مميز</label>
        </div>

        <div class="flex items-center pt-6">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $project->is_visible ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_visible" class="mr-2 block text-sm text-gray-700">مرئي</label>
        </div>
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-gray-700">صورة المشروع</label>
        @if(isset($projectImage) && $projectImage)
            <div class="mt-2 mb-3">
                <img src="{{ $projectImage }}" alt="الصورة الحالية" class="h-32 w-auto object-cover rounded-lg">
            </div>
        @endif
        <input type="file" name="image" id="image" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        @error('image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
