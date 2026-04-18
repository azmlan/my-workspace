<div class="space-y-6">
    <div>
        <label for="customer_id" class="block text-sm font-medium text-gray-700">العميل <span class="text-red-500">*</span></label>
        <select name="customer_id" id="customer_id" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('customer_id') border-red-500 @enderror">
            <option value="">اختر عميل</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id', $selectedCustomerId ?? ($clientProject->customer_id ?? '')) == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>
        @error('customer_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">العنوان <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $clientProject->title ?? '') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">الحالة <span class="text-red-500">*</span></label>
            <select name="status" id="status" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                @foreach($statuses as $status)
                    @if($status !== \App\Enums\ClientProjectStatus::Cancelled)
                        <option value="{{ $status->value }}" {{ old('status', ($clientProject->status ?? \App\Enums\ClientProjectStatus::Lead)->value) === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="type" class="block text-sm font-medium text-gray-700">النوع</label>
        <input type="text" name="type" id="type" value="{{ old('type', $clientProject->type ?? '') }}" placeholder="مثال: تطوير ويب، استشارات"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-500 @enderror">
        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $clientProject->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">تاريخ البدء</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($clientProject) && $clientProject->start_date ? $clientProject->start_date->format('Y-m-d') : '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
            @error('start_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="deadline" class="block text-sm font-medium text-gray-700">الموعد النهائي</label>
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', isset($clientProject) && $clientProject->deadline ? $clientProject->deadline->format('Y-m-d') : '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
            @error('deadline')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
