@extends('layouts.admin')

@section('title', 'إضافة مشروع')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.client-projects.index') }}" class="text-gray-600 hover:text-gray-900 ml-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">إضافة مشروع</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('dashboard.client-projects.store') }}" method="POST">
            @csrf
            @include('dashboard.client-projects._form')

            <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('dashboard.client-projects.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                    إنشاء مشروع
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
