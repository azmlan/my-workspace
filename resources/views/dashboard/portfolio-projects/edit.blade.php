@extends('layouts.admin')

@section('title', 'تعديل المشروع')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('backstage.portfolio-projects.index') }}" class="text-gray-500 hover:text-gray-700 ml-3">
            <x-heroicon-o-arrow-right class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">تعديل المشروع</h1>
    </div>

    <form action="{{ route('backstage.portfolio-projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        @include('dashboard.portfolio-projects._form')

        <div class="flex justify-end mt-6">
            <a href="{{ route('backstage.portfolio-projects.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md ml-3">
                إلغاء
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
