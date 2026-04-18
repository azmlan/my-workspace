@extends('layouts.admin')

@section('title', $clientProject->title)

@section('content')
@php
    $statusColors = [
        'gray' => 'bg-gray-100 text-gray-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'green' => 'bg-green-100 text-green-800',
        'red' => 'bg-red-100 text-red-800',
    ];
    $invoiceStatusColors = [
        'red' => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'green' => 'bg-green-100 text-green-800',
    ];
@endphp

<div>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard.customers.show', $clientProject->customer) }}" class="text-gray-600 hover:text-gray-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $clientProject->title }}</h1>
        </div>
        @if($clientProject->status !== \App\Enums\ClientProjectStatus::Cancelled)
        <div class="flex gap-3">
            <a href="{{ route('dashboard.client-projects.invoices.create', $clientProject) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                إضافة فاتورة
            </a>
            <a href="{{ route('dashboard.client-projects.edit', $clientProject) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                تعديل المشروع
            </a>
        </div>
        @endif
    </div>

    <!-- Project Details -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل المشروع</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">العميل</dt>
                <dd class="mt-1 text-sm">
                    <a href="{{ route('dashboard.customers.show', $clientProject->customer) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $clientProject->customer->name }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">الحالة</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$clientProject->status->color()] }}">
                        {{ $clientProject->status->label() }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">النوع</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $clientProject->type ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">تاريخ البدء</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $clientProject->start_date?->format('M d, Y') ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">الموعد النهائي</dt>
                <dd class="mt-1 text-sm {{ $clientProject->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                    {{ $clientProject->deadline?->format('M d, Y') ?? '-' }}
                    @if($clientProject->isOverdue())
                        <span class="text-xs">(متأخر)</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $clientProject->created_at->format('M d, Y') }}</dd>
            </div>
        </div>
        @if($clientProject->description)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500">الوصف</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $clientProject->description }}</dd>
            </div>
        @endif
        @if($clientProject->status === \App\Enums\ClientProjectStatus::Cancelled)
            @if($clientProject->cancellation_reason)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">سبب الإلغاء</dt>
                    <dd class="mt-1 text-sm text-red-700 whitespace-pre-wrap">{{ $clientProject->cancellation_reason }}</dd>
                </div>
            @endif
            @if($clientProject->cancellation_document_path)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">المستند الداعم</dt>
                    <dd class="mt-1">
                        <a href="{{ route('dashboard.client-projects.cancellation-document', $clientProject) }}" target="_blank"
                            class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            عرض المستند
                        </a>
                    </dd>
                </div>
            @endif
        @endif
    </div>

    <!-- Invoices -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">الفواتير</h2>
            <a href="{{ route('dashboard.client-projects.invoices.create', $clientProject) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md text-sm">
                إضافة فاتورة
            </a>
        </div>

        @if($clientProject->invoices->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الدفع</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clientProject->invoices as $invoice)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoiceStatusColors[$invoice->status->color()] }}">
                                        {{ $invoice->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->due_date?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->paid_at?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-left text-sm font-medium">
                                    <a href="{{ route('dashboard.client-projects.invoices.edit', [$clientProject, $invoice]) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">المجموع:</span>
                    <span class="font-medium text-gray-900">{{ number_format($clientProject->invoices->sum('amount'), 2) }} ريال</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-green-600">المدفوع:</span>
                    <span class="font-medium text-green-600">{{ number_format($clientProject->invoices->where('status', \App\Enums\InvoiceStatus::Paid)->sum('amount'), 2) }} ريال</span>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">لا توجد فواتير بعد.</p>
        @endif
    </div>
</div>
@endsection
