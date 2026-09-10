<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الفئات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, * { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-xl mx-auto p-4" x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">

    <header class="flex items-center justify-between py-3">
        <h1 class="text-base font-bold text-gray-800">إدارة الفئات</h1>
        <a href="{{ route('saharituwaiq.create') }}" class="text-sm text-indigo-600">رجوع للإدخال</a>
    </header>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 rounded mb-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 mb-4">
        <h2 class="text-sm font-semibold text-gray-800 mb-1">إضافة فئات (فئة واحدة في كل سطر)</h2>
        @if ($prefill)
            <p class="text-xs text-amber-600 mb-2">لم يتم العثور على "{{ $prefill }}" — أضفها بالأسفل إذا كانت فئة صحيحة.</p>
        @endif
        <form method="POST" action="{{ route('saharituwaiq.categories.store') }}">
            @csrf
            <textarea
                name="names"
                rows="6"
                placeholder="ملابس&#10;إلكترونيات&#10;أحذية"
                class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base"
            >{{ old('names', $prefill) }}</textarea>
            <button type="submit" class="mt-2 w-full bg-indigo-600 text-white rounded-md py-3 font-semibold">
                إضافة
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-sm font-semibold text-gray-800 px-3 py-2 border-b border-gray-100">
            الفئات الحالية ({{ $categories->count() }})
        </h2>
        <ul class="divide-y divide-gray-100">
            @forelse ($categories as $category)
                <li class="flex items-center justify-between px-3 py-2">
                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                    <button
                        type="button"
                        @click="showDeleteModal = true; deleteId = {{ $category->id }}; deleteName = '{{ addslashes($category->name) }}'"
                        class="text-red-600 hover:text-red-900 text-xs"
                    >حذف</button>
                </li>
            @empty
                <li class="px-3 py-4 text-center text-sm text-gray-500">لا توجد فئات بعد.</li>
            @endforelse
        </ul>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-500/75" @click="showDeleteModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showDeleteModal" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">حذف الفئة</h3>
                    <p class="mt-2 text-sm text-gray-500">هل أنت متأكد من حذف "<span x-text="deleteName"></span>"؟ الصفوف المحفوظة بهذه الفئة ستبقى كما هي.</p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="'{{ url('saharituwaiq/categories') }}/' + deleteId" method="POST" class="inline">
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
