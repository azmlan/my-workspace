<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">الاسم <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $customer->name ?? '') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">الهاتف <span class="text-red-500">*</span></label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone ?? '') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" value="{{ old('email', $customer->email ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="source" class="block text-sm font-medium text-gray-700">المصدر</label>
            <input type="text" name="source" id="source" value="{{ old('source', $customer->source ?? '') }}" placeholder="مثال: إحالة، موقع إلكتروني، وسائل التواصل"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('source') border-red-500 @enderror">
            @error('source')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="notes_general" class="block text-sm font-medium text-gray-700">ملاحظات عامة</label>
        <textarea name="notes_general" id="notes_general" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes_general') border-red-500 @enderror">{{ old('notes_general', $customer->notes_general ?? '') }}</textarea>
        @error('notes_general')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
