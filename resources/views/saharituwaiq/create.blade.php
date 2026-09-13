<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدخال المنتجات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, * { font-family: 'Tajawal', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen pb-28" x-data="entryForm({{ \Illuminate\Support\Js::from($categories) }})" x-cloak>

    <!-- Name gate -->
    <div x-show="!submittedBy" class="min-h-screen flex items-center justify-center p-6">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-sm">
            <h1 class="text-lg font-bold text-gray-800 mb-1">إدخال المنتجات</h1>
            <p class="text-sm text-gray-500 mb-4">أدخل اسمك مرة واحدة فقط</p>
            <input
                type="text"
                x-model="nameInput"
                @keydown.enter="setName()"
                placeholder="الاسم"
                class="w-full border border-gray-300 rounded-md px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            <button
                @click="setName()"
                class="mt-3 w-full bg-indigo-600 text-white rounded-md py-3 font-semibold disabled:opacity-40"
                :disabled="!nameInput.trim()"
            >
                ابدأ
            </button>
        </div>
    </div>

    <!-- Main form -->
    <div x-show="submittedBy" class="max-w-2xl mx-auto p-3 sm:p-4">

        <header class="flex items-center justify-between py-3">
            <div>
                <h1 class="text-base font-bold text-gray-800">إدخال المنتجات</h1>
                <p class="text-xs text-gray-500">
                    <span x-text="submittedBy"></span>
                    <button @click="changeName()" class="text-indigo-600 underline mr-1">تغيير</button>
                </p>
            </div>
            <div class="flex flex-col items-end gap-1">
                <a href="{{ route('saharituwaiq.review') }}" class="text-sm text-indigo-600 font-medium">
                    مراجعة السجلات
                </a>
                <a href="{{ route('saharituwaiq.categories.index') }}" target="_blank" class="text-xs text-gray-500 underline">
                    إدارة الفئات
                </a>
                <button @click="loadCategories()" type="button" class="text-xs text-gray-500 underline">
                    <span x-show="!categoriesRefreshed">تحديث قائمة الفئات</span>
                    <span x-show="categoriesRefreshed" class="text-green-600">✓ تم التحديث</span>
                </button>
                <button @click="clearDraft()" type="button" class="text-xs text-red-500 underline">
                    مسح البيانات المحفوظة مؤقتاً
                </button>
            </div>
        </header>

        <!-- Banners -->
        <div x-show="successMessage" x-cloak class="bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 rounded mb-3" x-text="successMessage"></div>
        <div x-show="globalError" x-cloak class="bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 rounded mb-3" x-text="globalError"></div>

        <p class="text-xs text-gray-400 mb-2">
            الاسم والفئة مطلوبان فقط للحفظ. باقي الحقول اختيارية ويمكن إكمالها لاحقاً من شاشة المراجعة —
            <span class="inline-block w-2 h-2 rounded-full bg-green-500 align-middle"></span> جاهز للتصدير،
            <span class="inline-block w-2 h-2 rounded-full bg-amber-400 align-middle"></span> يحتاج إكمال.
        </p>

        <!-- Rows -->
        <div class="space-y-3">
            <template x-for="(row, index) in rows" :key="row._id">
                <div class="bg-white rounded-lg shadow-sm border" :class="row._errors && Object.keys(row._errors).length ? 'border-red-400' : 'border-gray-200'">

                    <!-- Row header -->
                    <div class="flex items-center gap-2 px-3 py-2 cursor-pointer" @click="row._open = !row._open">
                        <span
                            class="w-2.5 h-2.5 rounded-full shrink-0"
                            :class="isRowComplete(row) ? 'bg-green-500' : 'bg-amber-400'"
                        ></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-text="row.product_name || 'صف جديد #' + (index + 1)"></p>
                            <p class="text-xs text-gray-400 truncate" x-text="row.sku ? ('SKU: ' + row.sku) : 'بدون SKU'"></p>
                        </div>
                        <span class="text-xs text-gray-400" x-text="row._open ? '▲' : '▼'"></span>
                    </div>

                    <!-- Row body -->
                    <div x-show="row._open" x-cloak class="px-3 pb-3 space-y-2 border-t border-gray-100 pt-2">

                        <template x-if="row._errors && Object.keys(row._errors).length">
                            <ul class="text-xs text-red-600 bg-red-50 rounded px-2 py-1 space-y-0.5">
                                <template x-for="msg in Object.values(row._errors)" :key="msg">
                                    <li x-text="msg"></li>
                                </template>
                            </ul>
                        </template>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">اسم المنتج *</label>
                            <input type="text" x-model="row.product_name" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">SKU</label>
                                <input type="text" x-model="row.sku" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">الباركود</label>
                                <input type="text" x-model="row.barcode" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-xs text-gray-500 mb-1">الفئة *</label>
                            <input
                                type="text"
                                x-model="row._categorySearch"
                                @click="row._categoryOpen = true"
                                @input="row.category = ''; row._categoryOpen = true"
                                placeholder="ابحث عن فئة..."
                                autocomplete="off"
                                class="w-full border rounded-md px-3 py-2.5 text-base"
                                :class="row.category ? 'border-gray-300' : 'border-amber-400'"
                            >
                            <div
                                x-show="row._categoryOpen"
                                @click.outside="row._categoryOpen = false"
                                x-cloak
                                class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto"
                            >
                                <template x-for="cat in filteredCategories(row)" :key="cat">
                                    <button
                                        type="button"
                                        @click="selectCategory(row, cat)"
                                        class="block w-full text-right px-3 py-2 text-sm hover:bg-gray-100"
                                        x-text="cat"
                                    ></button>
                                </template>
                                <div x-show="filteredCategories(row).length === 0" class="px-3 py-2 text-sm">
                                    <p class="text-gray-500">لا توجد نتائج.</p>
                                    <a
                                        :href="'{{ route('saharituwaiq.categories.index') }}?prefill=' + encodeURIComponent(row._categorySearch)"
                                        target="_blank"
                                        class="text-indigo-600 underline block mt-1"
                                    >+ إضافة فئة جديدة</a>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">اسم الخيار 1</label>
                                <input type="text" x-model="row.option_name_1" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 1</label>
                                <input type="text" x-model="row.option_value_1" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">اسم الخيار 2</label>
                                <input type="text" x-model="row.option_name_2" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 2</label>
                                <input type="text" x-model="row.option_value_2" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">اسم الخيار 3</label>
                                <input type="text" x-model="row.option_name_3" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">قيمة الخيار 3</label>
                                <input type="text" x-model="row.option_value_3" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">سعر التجزئة</label>
                                <input type="text" inputmode="decimal" x-model="row.retail_price" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">سعر التكلفة</label>
                                <input type="text" inputmode="decimal" x-model="row.cost_price" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">الكمية</label>
                                <input type="text" inputmode="numeric" x-model="row.quantity" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">الضريبة</label>
                                <select x-model="row.tax" @change="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base bg-white">
                                    <option value="">اختر</option>
                                    @foreach ($taxOptions as $tax)
                                        <option value="{{ $tax->value }}">{{ $tax->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <details class="text-sm">
                            <summary class="text-gray-500 cursor-pointer select-none">الوزن والأبعاد (اختياري)</summary>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">الوزن (جرام)</label>
                                    <input type="text" inputmode="numeric" x-model="row.weight_grams" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">الطول (سم)</label>
                                    <input type="text" inputmode="decimal" x-model="row.length_cm" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">العرض (سم)</label>
                                    <input type="text" inputmode="decimal" x-model="row.width_cm" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">الارتفاع (سم)</label>
                                    <input type="text" inputmode="decimal" x-model="row.height_cm" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                                </div>
                            </div>
                        </details>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">الوكيل</label>
                            <input type="text" x-model="row.agent" @input="saveDraft()" class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-base">
                        </div>

                        <div class="flex gap-2 pt-1">
                            <button @click="duplicateRow(index)" type="button" class="flex-1 text-sm bg-gray-100 text-gray-700 rounded-md py-2">تكرار الصف</button>
                            <button @click="removeRow(index)" type="button" class="flex-1 text-sm bg-red-50 text-red-600 rounded-md py-2" x-show="rows.length > 1">حذف الصف</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <button
            @click="addRow()"
            type="button"
            class="mt-3 w-full border-2 border-dashed border-indigo-300 text-indigo-600 rounded-lg py-3 font-medium"
        >
            + إضافة صف
        </button>
    </div>

    <!-- Sticky submit bar -->
    <div x-show="submittedBy" class="fixed bottom-0 inset-x-0 bg-white border-t border-gray-200 p-3 shadow-lg">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <span class="text-xs text-gray-500 shrink-0" x-text="rows.length + ' صف'"></span>
            <button
                @click="submitAll()"
                type="button"
                :disabled="submitting"
                class="flex-1 bg-indigo-600 text-white rounded-md py-3 font-semibold disabled:opacity-40"
            >
                <span x-show="!submitting">حفظ الكل</span>
                <span x-show="submitting">جارٍ الحفظ...</span>
            </button>
        </div>
    </div>

    <script>
        function blankRow(prev) {
            return {
                _id: Math.random().toString(36).slice(2),
                _open: true,
                _errors: {},
                _categorySearch: prev ? prev.category : '',
                _categoryOpen: false,
                product_name: '',
                sku: '',
                option_name_1: prev ? prev.option_name_1 : '',
                option_value_1: '',
                option_name_2: prev ? prev.option_name_2 : '',
                option_value_2: '',
                option_name_3: prev ? prev.option_name_3 : '',
                option_value_3: '',
                retail_price: '',
                cost_price: '',
                category: prev ? prev.category : '',
                quantity: '',
                barcode: '',
                tax: '',
                agent: '',
                weight_grams: '',
                length_cm: '',
                width_cm: '',
                height_cm: '',
            };
        }

        function entryForm(initialCategories) {
            return {
                submittedBy: '',
                nameInput: '',
                rows: [],
                categories: initialCategories || [],
                categoriesRefreshed: false,
                submitting: false,
                successMessage: '',
                globalError: '',
                _saveTimer: null,

                init() {
                    this.submittedBy = localStorage.getItem('saharituwaiq_name') || '';
                    const draft = localStorage.getItem('saharituwaiq_rows');
                    if (draft) {
                        try {
                            const parsed = JSON.parse(draft);
                            if (Array.isArray(parsed) && parsed.length) {
                                this.rows = parsed.map(r => {
                                    const merged = { ...blankRow(null), ...r, _errors: {}, _open: false };
                                    merged._categorySearch = merged.category || '';
                                    merged._categoryOpen = false;
                                    return merged;
                                });
                                this.rows[this.rows.length - 1]._open = true;
                            }
                        } catch (e) {}
                    }
                    if (!this.rows.length) {
                        this.rows = [blankRow(null)];
                    }
                },

                filteredCategories(row) {
                    const query = (row._categorySearch || '').trim();
                    if (!query) return this.categories;
                    return this.categories.filter(c => c.includes(query));
                },

                selectCategory(row, cat) {
                    row.category = cat;
                    row._categorySearch = cat;
                    row._categoryOpen = false;
                    this.saveDraft();
                },

                async loadCategories() {
                    try {
                        const response = await fetch('{{ route('saharituwaiq.categories.json') }}');
                        this.categories = await response.json();
                        this.categoriesRefreshed = true;
                        setTimeout(() => { this.categoriesRefreshed = false; }, 1500);
                    } catch (e) {}
                },

                setName() {
                    const name = this.nameInput.trim();
                    if (!name) return;
                    this.submittedBy = name;
                    localStorage.setItem('saharituwaiq_name', name);
                },

                changeName() {
                    this.nameInput = this.submittedBy;
                    this.submittedBy = '';
                },

                addRow() {
                    const prev = this.rows[this.rows.length - 1];
                    this.rows.forEach(r => r._open = false);
                    this.rows.push(blankRow(prev));
                    this.saveDraft();
                },

                duplicateRow(index) {
                    const source = this.rows[index];
                    const copy = { ...source, _id: Math.random().toString(36).slice(2), _errors: {}, _open: true, _categoryOpen: false, sku: '', barcode: '' };
                    this.rows.forEach(r => r._open = false);
                    this.rows.splice(index + 1, 0, copy);
                    this.saveDraft();
                },

                removeRow(index) {
                    if (this.rows.length <= 1) return;
                    this.rows.splice(index, 1);
                    this.saveDraft();
                },

                isRowComplete(row) {
                    return !!(row.product_name && row.sku && row.retail_price !== '' && row.quantity !== '' && row.tax);
                },

                saveDraft() {
                    clearTimeout(this._saveTimer);
                    this._saveTimer = setTimeout(() => {
                        const plain = this.rows.map(({ _id, _open, _errors, _categorySearch, _categoryOpen, ...rest }) => rest);
                        localStorage.setItem('saharituwaiq_rows', JSON.stringify(plain));
                    }, 250);
                },

                clearDraft() {
                    const confirmed = confirm(
                        'سيتم حذف جميع الصفوف المحفوظة مؤقتاً في هذا الجهاز والتي لم يتم إرسالها بعد. لا يمكن التراجع عن هذا الإجراء. هل تريد المتابعة؟'
                    );
                    if (!confirmed) return;

                    clearTimeout(this._saveTimer);
                    localStorage.removeItem('saharituwaiq_rows');
                    this.rows = [blankRow(null)];
                    this.successMessage = '';
                    this.globalError = '';
                },

                async submitAll() {
                    if (this.submitting) return;
                    this.globalError = '';
                    this.successMessage = '';
                    this.rows.forEach(r => r._errors = {});

                    this.submitting = true;

                    const payload = {
                        submitted_by: this.submittedBy,
                        rows: this.rows.map(({ _id, _open, _errors, _categorySearch, _categoryOpen, ...rest }) => rest),
                    };

                    try {
                        const response = await fetch('{{ route('saharituwaiq.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(payload),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message;
                            const last = this.rows[this.rows.length - 1];
                            this.rows = [blankRow(last)];
                            localStorage.removeItem('saharituwaiq_rows');
                        } else if (response.status === 422) {
                            this.applyErrors(data.errors || {});
                            this.globalError = data.message || 'يوجد أخطاء في النموذج، تحقق من الصفوف المحددة.';
                        } else {
                            this.globalError = data.message || 'حدث خطأ غير متوقع. حاول مرة أخرى.';
                        }
                    } catch (e) {
                        this.globalError = 'تعذر الاتصال بالخادم. تحقق من الإنترنت وحاول مرة أخرى. لم يتم فقدان أي بيانات.';
                    } finally {
                        this.submitting = false;
                    }
                },

                applyErrors(errors) {
                    Object.entries(errors).forEach(([key, messages]) => {
                        const match = key.match(/^rows\.(\d+)\.(.+)$/);
                        if (match) {
                            const [, idx, field] = match;
                            if (this.rows[idx]) {
                                this.rows[idx]._errors[field] = Array.isArray(messages) ? messages[0] : messages;
                                this.rows[idx]._open = true;
                            }
                        }
                    });
                },
            };
        }
    </script>
</body>
</html>
