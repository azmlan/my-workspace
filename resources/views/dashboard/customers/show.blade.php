@extends('layouts.admin')

@section('title', $customer->name)

@section('content')
<div x-data="{ showDeleteModal: false }">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard.customers.index') }}" class="text-gray-600 hover:text-gray-900 ml-4">
                <x-heroicon-o-arrow-right class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dashboard.customers.edit', $customer) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                تعديل العميل
            </a>
            <button type="button" @click="showDeleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                حذف
            </button>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">معلومات العميل</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">الهاتف</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $customer->email ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">المصدر</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $customer->source ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">عميل منذ</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</dd>
            </div>
        </div>
        @if($customer->notes_general)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500">ملاحظات عامة</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $customer->notes_general }}</dd>
            </div>
        @endif
    </div>

    <!-- Invoices Summary -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">ملخص الفواتير</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-gray-500">المجموع</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($invoicesSummary['total'], 2) }} ريال</dd>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-green-600">المدفوع</dt>
                <dd class="mt-1 text-2xl font-semibold text-green-700">{{ number_format($invoicesSummary['paid'], 2) }} ريال</dd>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-red-600">غير المدفوع</dt>
                <dd class="mt-1 text-2xl font-semibold text-red-700">{{ number_format($invoicesSummary['unpaid'], 2) }} ريال</dd>
            </div>
        </div>
    </div>

    <!-- Client Projects -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">المشاريع</h2>
            <a href="{{ route('dashboard.client-projects.create', ['customer_id' => $customer->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md text-sm">
                إضافة مشروع
            </a>
        </div>
        @if($customer->clientProjects->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموعد النهائي</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفواتير</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->clientProjects as $project)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $project->title }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'gray' => 'bg-gray-100 text-gray-800',
                                            'blue' => 'bg-blue-100 text-blue-800',
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'green' => 'bg-green-100 text-green-800',
                                            'red' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status->color()] }}">
                                        {{ $project->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm {{ $project->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    {{ $project->deadline?->format('M d, Y') ?? '-' }}
                                    @if($project->isOverdue())
                                        <span class="text-xs">(متأخر)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $project->invoices->count() }} فاتورة
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-left text-sm font-medium">
                                    <a href="{{ route('dashboard.client-projects.edit', $project) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">لا توجد مشاريع بعد. <a href="{{ route('dashboard.client-projects.create', ['customer_id' => $customer->id]) }}" class="text-blue-600 hover:text-blue-800">إضافة مشروع</a>.</p>
        @endif
    </div>

    <!-- Notes -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">الملاحظات</h2>

        <!-- Add Note Form -->
        <form action="{{ route('dashboard.customers.notes.store', $customer) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-4">
                <textarea name="body" rows="2" placeholder="إضافة ملاحظة..." required
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('body') border-red-500 @enderror"></textarea>
                <button type="submit" class="self-end bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                    إضافة ملاحظة
                </button>
            </div>
            @error('body')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>

        <!-- Notes List -->
        @if($customer->notes->count())
            <div class="space-y-4">
                @foreach($customer->notes as $note)
                    <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $note->body }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $note->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <form action="{{ route('dashboard.customers.notes.destroy', [$customer, $note]) }}" method="POST" class="mr-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('حذف هذه الملاحظة؟')">
                                حذف
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">لا توجد ملاحظات بعد.</p>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">حذف العميل</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">هل أنت متأكد من حذف "{{ $customer->name }}"؟ سيتم أيضاً حذف جميع المشاريع والفواتير والملاحظات الخاصة بهم. لا يمكن التراجع عن هذا الإجراء.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('dashboard.customers.destroy', $customer) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm">
                            حذف
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
