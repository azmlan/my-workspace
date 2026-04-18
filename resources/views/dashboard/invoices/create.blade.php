@extends('layouts.admin')

@section('title', 'إضافة فاتورة')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.client-projects.show', $clientProject) }}" class="text-gray-600 hover:text-gray-900 ml-4">
            <x-heroicon-o-arrow-right class="w-5 h-5" />
        </a>
        <div class="text-right">
            <h1 class="text-2xl font-bold text-gray-900">إضافة فاتورة</h1>
            <p class="text-sm text-gray-500">للمشروع: {{ $clientProject->title }} — سيتم توليد رقم الفاتورة تلقائياً</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('dashboard.client-projects.invoices.store', $clientProject) }}" method="POST">
            @csrf
            @include('dashboard.invoices._form')

            <div class="mt-6 flex justify-end space-x-reverse space-x-3">
                <a href="{{ route('dashboard.client-projects.show', $clientProject) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                    إنشاء فاتورة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
