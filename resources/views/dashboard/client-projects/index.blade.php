@extends('layouts.admin')

@section('title', 'مشاريع العملاء')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">مشاريع العملاء</h1>
        <a href="{{ route('backstage.client-projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
            إضافة مشروع جديد
        </a>
    </div>

    <!-- Filter by Status -->
    <div class="mb-6">
        <form method="GET" action="{{ route('backstage.client-projects.index') }}" class="flex gap-4 items-center">
            <label for="status" class="text-sm font-medium text-gray-700">تصفية حسب الحالة:</label>
            <select name="status" id="status" onchange="this.form.submit()"
                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">جميع الحالات</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            @if(request('status'))
                <a href="{{ route('backstage.client-projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    إزالة التصفية
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموعد النهائي</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clientProjects as $project)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $project->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('backstage.customers.show', $project->customer) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ $project->customer->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $project->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                            {{ $project->deadline?->format('M d, Y') ?? '-' }}
                            @if($project->isOverdue())
                                <span class="text-xs">(متأخر)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <a href="{{ route('backstage.client-projects.show', $project) }}" class="text-gray-600 hover:text-gray-900 ml-3">عرض</a>
                            <a href="{{ route('backstage.client-projects.edit', $project) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            لا يوجد مشاريع. <a href="{{ route('backstage.client-projects.create') }}" class="text-blue-600 hover:text-blue-800">إضافة واحد</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clientProjects->hasPages())
        <div class="mt-6">
            {{ $clientProjects->links() }}
        </div>
    @endif

</div>
@endsection
