<div class="space-y-6">
    <div>
        <label for="client_name" class="block text-sm font-medium text-gray-700">اسم العميل</label>
        <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $testimonial->client_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_name') border-red-500 @enderror">
        @error('client_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="client_role" class="block text-sm font-medium text-gray-700">منصب العميل (اختياري)</label>
            <input type="text" name="client_role" id="client_role" value="{{ old('client_role', $testimonial->client_role ?? '') }}" placeholder="مدير تنفيذي، مطور، الخ"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_role') border-red-500 @enderror">
            @error('client_role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="client_company" class="block text-sm font-medium text-gray-700">شركة العميل (اختياري)</label>
            <input type="text" name="client_company" id="client_company" value="{{ old('client_company', $testimonial->client_company ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_company') border-red-500 @enderror">
            @error('client_company')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني (اختياري)</label>
        <input type="email" name="email" id="email" value="{{ old('email', $testimonial->email ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="body" class="block text-sm font-medium text-gray-700">المحتوى</label>
        <textarea name="body" id="body" rows="5"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('body') border-red-500 @enderror">{{ old('body', $testimonial->body ?? '') }}</textarea>
        @error('body')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="rating" class="block text-sm font-medium text-gray-700">التقييم (اختياري)</label>
            <select name="rating" id="rating"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('rating') border-red-500 @enderror">
                <option value="">لا يوجد تقييم</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? '') == $i ? 'selected' : '' }}>{{ $i }} {{ $i === 1 ? 'نجمة' : 'نجوم' }}</option>
                @endfor
            </select>
            @error('rating')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700">الترتيب</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sort_order') border-red-500 @enderror">
            @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center pt-6">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $testimonial->is_visible ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_visible" class="mr-2 block text-sm text-gray-700">مرئي</label>
        </div>
    </div>

    <div>
        <label for="photo" class="block text-sm font-medium text-gray-700">صورة العميل (اختياري)</label>
        @if(isset($testimonialPhoto) && $testimonialPhoto)
            <div class="mt-2 mb-3">
                <img src="{{ $testimonialPhoto }}" alt="الصورة الحالية" class="h-20 w-20 object-cover rounded-full">
            </div>
        @endif
        <input type="file" name="photo" id="photo" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        @error('photo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
