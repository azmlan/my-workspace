<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 text-right">المبلغ <span class="text-red-500">*</span></label>
            <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $invoice->amount ?? '') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right @error('amount') border-red-500 @enderror">
            @error('amount')
                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="currency" class="block text-sm font-medium text-gray-700 text-right">العملة <span class="text-red-500">*</span></label>
            <input type="text" name="currency" id="currency" value="{{ old('currency', $invoice->currency ?? 'SAR') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right @error('currency') border-red-500 @enderror">
            @error('currency')
                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 text-right">الحالة <span class="text-red-500">*</span></label>
            <select name="status" id="status" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right @error('status') border-red-500 @enderror">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ old('status', ($invoice->status ?? \App\Enums\InvoiceStatus::Unpaid)->value) === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700 text-right">تاريخ الاستحقاق</label>
            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', isset($invoice) && $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right @error('due_date') border-red-500 @enderror">
            @error('due_date')
                <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 text-right">الملاحظات</label>
        <textarea name="notes" id="notes" rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right @error('notes') border-red-500 @enderror">{{ old('notes', $invoice->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
        @enderror
    </div>
</div>
