@extends('layouts.admin')

@section('title', 'سجل الأحداث')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">سجل الأحداث</h1>
        <span class="text-sm text-gray-500">{{ $logs->total() }} حدث</span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('dashboard.audit-logs.index') }}" class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">النوع</label>
            <select name="subject_type" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">الكل</option>
                <option value="ClientProject" @selected(request('subject_type') === 'ClientProject')>مشروع عميل</option>
                <option value="Invoice" @selected(request('subject_type') === 'Invoice')>فاتورة</option>
                <option value="Customer" @selected(request('subject_type') === 'Customer')>عميل</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">الإجراء</label>
            <select name="action" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">الكل</option>
                <option value="created" @selected(request('action') === 'created')>إنشاء</option>
                <option value="updated" @selected(request('action') === 'updated')>تعديل</option>
                <option value="status_changed" @selected(request('action') === 'status_changed')>تغيير الحالة</option>
                <option value="cancelled" @selected(request('action') === 'cancelled')>إلغاء</option>
                <option value="deleted" @selected(request('action') === 'deleted')>حذف</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                تصفية
            </button>
            @if(request('subject_type') || request('action'))
                <a href="{{ route('dashboard.audit-logs.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-200">
                    مسح
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($logs->isEmpty())
            <div class="text-center py-16 text-gray-400">لا توجد أحداث مسجلة بعد.</div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراء</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السجل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التفاصيل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                        @php
                            $actionConfig = match($log->action) {
                                'created'        => ['label' => 'إنشاء',         'class' => 'bg-green-100 text-green-800'],
                                'updated'        => ['label' => 'تعديل',          'class' => 'bg-blue-100 text-blue-800'],
                                'status_changed' => ['label' => 'تغيير الحالة',  'class' => 'bg-yellow-100 text-yellow-800'],
                                'cancelled'      => ['label' => 'إلغاء',          'class' => 'bg-red-100 text-red-800'],
                                'deleted'        => ['label' => 'حذف',            'class' => 'bg-gray-200 text-gray-700'],
                                default          => ['label' => $log->action,     'class' => 'bg-gray-100 text-gray-600'],
                            };

                            $typeLabel = match($log->subject_type) {
                                'ClientProject' => 'مشروع عميل',
                                'Invoice'       => 'فاتورة',
                                'Customer'      => 'عميل',
                                default         => $log->subject_type,
                            };

                            $subjectUrl = null;
                            if ($log->action !== 'deleted') {
                                $subjectUrl = match($log->subject_type) {
                                    'ClientProject' => route('dashboard.client-projects.show', $log->subject_id),
                                    'Customer'      => route('dashboard.customers.show', $log->subject_id),
                                    default         => null,
                                };
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $actionConfig['class'] }}">
                                    {{ $actionConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $typeLabel }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($subjectUrl)
                                    <a href="{{ $subjectUrl }}" class="text-blue-600 hover:underline">
                                        {{ $log->subject_label }}
                                    </a>
                                @else
                                    <span class="text-gray-500">{{ $log->subject_label }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($log->meta)
                                    @if($log->action === 'status_changed')
                                        <span class="line-through text-gray-400">{{ $log->meta['from'] }}</span>
                                        → {{ $log->meta['to'] }}
                                    @elseif($log->action === 'cancelled')
                                        {{ $log->meta['reason'] }}
                                    @elseif($log->action === 'created' && isset($log->meta['amount']))
                                        {{ number_format($log->meta['amount'], 2) }} {{ $log->meta['currency'] }}
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
