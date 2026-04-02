@extends('layouts.admin')

@section('title', 'إعدادات من انا')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">إعدادات من انا</h1>

    <form action="{{ route('dashboard.settings.about.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="bio_full" class="block text-sm font-medium text-gray-700">النبذة التعريفية الكاملة</label>
            <textarea name="bio_full" id="bio_full" rows="10"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('bio_full') border-red-500 @enderror">{{ old('bio_full', $settings->bio_full) }}</textarea>
            @error('bio_full')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700">الصورة</label>
            @if($aboutPhoto)
                <div class="mt-2 mb-3">
                    <img src="{{ $aboutPhoto }}" alt="الصورة الحالية" class="h-24 w-24 object-cover rounded-lg">
                </div>
            @endif
            <input type="file" name="photo" id="photo" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('photo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                حفظ الإعدادات
            </button>
        </div>
    </form>
</div>
@endsection
