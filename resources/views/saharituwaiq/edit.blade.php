<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعديل صف</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, * { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen pb-10">

    <div class="max-w-xl mx-auto p-4">
        <header class="flex items-center justify-between py-3">
            <h1 class="text-base font-bold text-gray-800">تعديل صف</h1>
            <a href="{{ route('saharituwaiq.review') }}" class="text-sm text-indigo-600">رجوع للمراجعة</a>
        </header>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 rounded mb-3">
                <ul class="list-disc pr-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('saharituwaiq.update', $entry) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 space-y-2">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs text-gray-500 mb-1">اسم المنتج *</label>
                <input type="text" name="product_name" value="{{ old('product_name', $entry->product_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">الفئة *</label>
                <select name="category" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base bg-white">
                    <option value="">اختر</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $entry->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @if ($categories->isEmpty())
                    <p class="text-xs text-amber-600 mt-1">
                        لا توجد فئات بعد.
                        <a href="{{ route('saharituwaiq.categories.index') }}" target="_blank" class="underline">أضف فئة</a>
                        ثم أعد تحميل الصفحة.
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $entry->sku) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">الباركود</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $entry->barcode) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">اسم الخيار 1</label>
                    <input type="text" name="option_name_1" value="{{ old('option_name_1', $entry->option_name_1) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 1</label>
                    <input type="text" name="option_value_1" value="{{ old('option_value_1', $entry->option_value_1) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">اسم الخيار 2</label>
                    <input type="text" name="option_name_2" value="{{ old('option_name_2', $entry->option_name_2) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 2</label>
                    <input type="text" name="option_value_2" value="{{ old('option_value_2', $entry->option_value_2) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">اسم الخيار 3</label>
                    <input type="text" name="option_name_3" value="{{ old('option_name_3', $entry->option_name_3) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 3</label>
                    <input type="text" name="option_value_3" value="{{ old('option_value_3', $entry->option_value_3) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">سعر التجزئة</label>
                    <input type="text" inputmode="decimal" name="retail_price" value="{{ old('retail_price', $entry->retail_price) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">سعر التكلفة</label>
                    <input type="text" inputmode="decimal" name="cost_price" value="{{ old('cost_price', $entry->cost_price) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">الكمية</label>
                    <input type="text" inputmode="numeric" name="quantity" value="{{ old('quantity', $entry->quantity) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">الضريبة</label>
                    <select name="tax" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base bg-white">
                        <option value="">اختر</option>
                        @foreach ($taxOptions as $tax)
                            <option value="{{ $tax->value }}" @selected(old('tax', $entry->tax) === $tax->value)>{{ $tax->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <details class="text-sm" open>
                <summary class="text-gray-500 cursor-pointer select-none">الوزن والأبعاد (اختياري)</summary>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">الوزن (جرام)</label>
                        <input type="text" inputmode="numeric" name="weight_grams" value="{{ old('weight_grams', $entry->weight_grams) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">الطول (سم)</label>
                        <input type="text" inputmode="decimal" name="length_cm" value="{{ old('length_cm', $entry->length_cm) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">العرض (سم)</label>
                        <input type="text" inputmode="decimal" name="width_cm" value="{{ old('width_cm', $entry->width_cm) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">الارتفاع (سم)</label>
                        <input type="text" inputmode="decimal" name="height_cm" value="{{ old('height_cm', $entry->height_cm) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                    </div>
                </div>
            </details>

            <div>
                <label class="block text-xs text-gray-500 mb-1">الوكيل</label>
                <input type="text" name="agent" value="{{ old('agent', $entry->agent) }}" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white rounded-md py-3 font-semibold mt-2">
                حفظ التعديلات
            </button>
        </form>
    </div>
</body>
</html>
