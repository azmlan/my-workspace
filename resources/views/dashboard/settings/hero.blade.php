@extends('layouts.admin')

@section('title', 'إعدادات الواجهة')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">إعدادات الواجهة</h1>

    <form action="{{ route('dashboard.settings.hero.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="full_name" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $settings->full_name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('full_name') border-red-500 @enderror">
            @error('full_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tagline" class="block text-sm font-medium text-gray-700">العنوان الفرعي</label>
            <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $settings->tagline) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tagline') border-red-500 @enderror">
            @error('tagline')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="bio_short" class="block text-sm font-medium text-gray-700">نبذة مختصرة</label>
            <textarea name="bio_short" id="bio_short" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('bio_short') border-red-500 @enderror">{{ old('bio_short', $settings->bio_short) }}</textarea>
            @error('bio_short')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="github_url" class="block text-sm font-medium text-gray-700">رابط GitHub</label>
                <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $settings->github_url) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('github_url') border-red-500 @enderror">
                @error('github_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="linkedin_url" class="block text-sm font-medium text-gray-700">رابط LinkedIn</label>
                <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('linkedin_url') border-red-500 @enderror">
                @error('linkedin_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="twitter_url" class="block text-sm font-medium text-gray-700">رابط Twitter</label>
                <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('twitter_url') border-red-500 @enderror">
                @error('twitter_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email_display" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email_display" id="email_display" value="{{ old('email_display', $settings->email_display) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email_display') border-red-500 @enderror">
                @error('email_display')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700">الصورة</label>
                @if($heroPhoto)
                    <div class="mt-2 mb-3">
                        <img src="{{ $heroPhoto }}" alt="الصورة الحالية" class="h-24 w-24 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cv_file" class="block text-sm font-medium text-gray-700">ملف السيرة الذاتية (PDF, DOC, DOCX)</label>
                @if($cvFile)
                    <div class="mt-2 mb-3">
                        <a href="{{ $cvFile->getUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                            {{ $cvFile->file_name }} ({{ number_format($cvFile->size / 1024, 1) }} KB)
                        </a>
                    </div>
                @endif
                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx"
                    class="mt-1 block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('cv_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                حفظ الإعدادات
            </button>
        </div>
    </form>
</div>
@endsection
