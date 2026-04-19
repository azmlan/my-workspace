@extends('layouts.admin')

@section('title', 'تعديل المشروع')

@section('content')
<div x-data="{ showDeleteModal: false, showCancelModal: false }">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('backstage.customers.show', $clientProject->customer) }}" class="text-gray-600 hover:text-gray-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">تعديل المشروع</h1>
        </div>
        <div class="flex gap-3">
            @if($clientProject->status !== \App\Enums\ClientProjectStatus::Cancelled)
                <button type="button" @click="showCancelModal = true" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-md">
                    إلغاء المشروع
                </button>
            @endif
            <button type="button" @click="showDeleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                حذف المشروع
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('backstage.client-projects.update', $clientProject) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('dashboard.client-projects._form')

                    <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                        <a href="{{ route('backstage.customers.show', $clientProject->customer) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
                            إلغاء
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            تحديث المشروع
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">الفواتير</h2>
                    <a href="{{ route('backstage.client-projects.invoices.create', $clientProject) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded-md text-sm">
                        إضافة فاتورة
                    </a>
                </div>

                @if($clientProject->invoices->count())
                    <div class="space-y-3">
                        @foreach($clientProject->invoices as $invoice)
                            @php
                                $invoiceStatusColors = [
                                    'red' => 'bg-red-100 text-red-800',
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    'green' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $invoiceStatusColors[$invoice->status->color()] }}">
                                            {{ $invoice->status->label() }}
                                        </span>
                                    </div>
                                    <a href="{{ route('backstage.client-projects.invoices.edit', [$clientProject, $invoice]) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        تعديل
                                    </a>
                                </div>
                                @if($invoice->due_date)
                                    <p class="text-xs text-gray-500 mt-1">تاريخ الاستحقاق: {{ $invoice->due_date->format('M d, Y') }}</p>
                                @endif
                                @if($invoice->paid_at)
                                    <p class="text-xs text-green-600 mt-1">تاريخ الدفع: {{ $invoice->paid_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">المجموع:</span>
                            <span class="font-medium text-gray-900">{{ number_format($clientProject->invoices->sum('amount'), 2) }} ريال</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-green-600">المدفوع:</span>
                            <span class="font-medium text-green-600">{{ number_format($clientProject->invoices->where('status', \App\Enums\InvoiceStatus::Paid)->sum('amount'), 2) }} ريال</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">لا توجد فواتير بعد.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Project Modal -->
    <div x-show="showCancelModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCancelModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showCancelModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showCancelModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('backstage.client-projects.cancel', $clientProject) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-orange-100">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">إلغاء المشروع</h3>
                                <p class="mt-1 text-sm text-red-600 font-medium">تحذير: هذا الإجراء لا يمكن التراجع عنه.</p>
                                <p class="mt-1 text-sm text-gray-500">سيتم تغيير حالة المشروع إلى "ملغي" بشكل دائم.</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">سبب الإلغاء <span class="text-red-500">*</span></label>
                                <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label for="cancellation_document" class="block text-sm font-medium text-gray-700">مستند داعم (اختياري — صورة أو PDF)</label>
                                <input type="file" name="cancellation_document" id="cancellation_document" accept=".jpg,.jpeg,.png,.pdf"
                                    class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 sm:w-auto sm:text-sm">
                            تأكيد الإلغاء
                        </button>
                        <button type="button" @click="showCancelModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            تراجع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">حذف المشروع</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">هل أنت متأكد من حذف "{{ $clientProject->title }}"؟ سيتم أيضًا حذف جميع الفواتير المرتبطة. لا يمكن التراجع عن هذا الإجراء.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('backstage.client-projects.destroy', $clientProject) }}" method="POST" class="inline">
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
