@extends('layouts.admin')

@section('title', 'تعديل الفاتورة')

@section('content')
<div x-data="{ showDeleteModal: false }">
    <div class="max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <a href="{{ route('dashboard.client-projects.edit', $clientProject) }}" class="text-gray-600 hover:text-gray-900 ml-4">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div class="text-right">
                    <h1 class="text-2xl font-bold text-gray-900">تعديل الفاتورة</h1>
                    <p class="text-sm text-gray-500">للمشروع: {{ $clientProject->title }}</p>
                </div>
            </div>
            <button type="button" @click="showDeleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                حذف
            </button>
        </div>

        @if($invoice->paid_at)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 text-right">
                تم تحديد هذه الفاتورة كمدفوعة بتاريخ {{ $invoice->paid_at->format('Y/m/d \ا\ل\س\ا\ع\ة g:i A') }}.
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('dashboard.client-projects.invoices.update', [$clientProject, $invoice]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('dashboard.invoices._form')

                <div class="mt-6 flex justify-end space-x-reverse space-x-3">
                    <a href="{{ route('dashboard.client-projects.edit', $clientProject) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        تحديث الفاتورة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start sm:flex-row-reverse">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">حذف الفاتورة</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">هل أنت متأكد من حذف هذه الفاتورة بمبلغ {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}؟ لا يمكن التراجع عن هذا الإجراء.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('dashboard.client-projects.invoices.destroy', [$clientProject, $invoice]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
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
