@extends('layouts.admin')

@section('title', 'إضافة عميل جديد')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('backstage.customers.index') }}" class="text-gray-600 hover:text-gray-900 ml-4">
            <x-heroicon-o-arrow-right class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">إضافة عميل جديد</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('backstage.customers.store') }}" method="POST">
            @csrf
            @include('dashboard.customers._form')

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('backstage.customers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                    إنشاء عميل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
