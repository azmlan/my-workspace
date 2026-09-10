<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مراجعة السجلات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, * { font-family: 'Tajawal', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-5xl mx-auto p-4" x-data="{ showDeleteModal: false, deleteId: null, deleteLabel: '' }">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">مراجعة السجلات</h1>
            <p class="text-sm text-gray-500">
                إجمالي الصفوف: {{ $total }}
                @if ($incompleteCount > 0)
                    <span class="text-amber-600">— {{ $incompleteCount }} صف غير مكتمل (يحتاج SKU/سعر/كمية/ضريبة قبل التصدير لـ Rewaa)</span>
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('saharituwaiq.create') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md text-sm">
                رجوع للإدخال
            </a>
            <a href="{{ route('saharituwaiq.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                تصدير Rewaa
            </a>
            <a href="{{ route('saharituwaiq.export-full') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                تصدير كامل (CSV)
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('saharituwaiq.review') }}" class="flex flex-wrap gap-3 mb-4">
        <select name="category" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
            <option value="">كل الفئات</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="submitted_by" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
            <option value="">كل المدخلين</option>
            @foreach ($submitters as $sub)
                <option value="{{ $sub }}" @selected(request('submitted_by') === $sub)>{{ $sub }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
            <option value="">كل الحالات</option>
            <option value="complete" @selected(request('status') === 'complete')>مكتمل</option>
            <option value="incomplete" @selected(request('status') === 'incomplete')>غير مكتمل</option>
        </select>
        @if (request('category') || request('submitted_by') || request('status'))
            <a href="{{ route('saharituwaiq.review') }}" class="text-sm text-gray-500 self-center underline">مسح الفلاتر</a>
        @endif
    </form>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">المنتج</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">SKU</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الفئة</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">السعر</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الكمية</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الضريبة</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">المدخل</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">التاريخ</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">إجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($entries as $entry)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if ($entry->isReadyForExport())
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500" title="مكتمل"></span>
                            @else
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400" title="غير مكتمل"></span>
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->product_name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap font-mono text-xs">{{ $entry->sku ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->category ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->retail_price !== null ? number_format((float) $entry->retail_price, 2) : '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->quantity ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->tax ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $entry->submitted_by }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-gray-500">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-left">
                            <a href="{{ route('saharituwaiq.edit', $entry) }}" class="text-indigo-600 hover:text-indigo-900 text-xs ml-2">تعديل</a>
                            <button
                                type="button"
                                @click="showDeleteModal = true; deleteId = {{ $entry->id }}; deleteLabel = '{{ addslashes($entry->product_name) }} ({{ addslashes($entry->sku ?? '') }})'"
                                class="text-red-600 hover:text-red-900 text-xs"
                            >حذف</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($entries->hasPages())
        <div class="mt-4">{{ $entries->links() }}</div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-500/75" @click="showDeleteModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showDeleteModal" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">حذف الصف</h3>
                    <p class="mt-2 text-sm text-gray-500">هل أنت متأكد من حذف "<span x-text="deleteLabel"></span>"؟ لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="'{{ url('saharituwaiq') }}/' + deleteId" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:mr-3 sm:w-auto sm:text-sm">
                            حذف
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
