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
            <a href="{{ route('backstage.customers.show', $clientProject->customer) }}" class="text-gray-600 hover:text-gray-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $clientProject->title }}</h1>
        </div>
        @if($clientProject->status !== \App\Enums\ClientProjectStatus::Cancelled)
        <div class="flex gap-3">
            <a href="{{ route('backstage.client-projects.invoices.create', $clientProject) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                إضافة فاتورة
            </a>
            <a href="{{ route('backstage.client-projects.edit', $clientProject) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
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
                    <a href="{{ route('backstage.customers.show', $clientProject->customer) }}" class="text-blue-600 hover:text-blue-800">
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
                <dt class="text-sm font-medium text-gray-500">المنصة</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @php
                        $platformLabel = collect(config('project_types.platforms'))->firstWhere('value', $clientProject->platform)['label'] ?? null;
                    @endphp
                    {{ $platformLabel ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">المجال</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @php
                        $domainLabel = collect(config('project_types.domains'))->firstWhere('value', $clientProject->domain)['label'] ?? null;
                    @endphp
                    {{ $domainLabel ?? '-' }}
                </dd>
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
                        <a href="{{ route('backstage.client-projects.cancellation-document', $clientProject) }}" target="_blank"
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

    <!-- Files -->
    <div class="bg-white shadow rounded-lg p-6 mb-6" x-data="{ showDeleteModal: false, deleteUrl: '' }">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">الملفات المرفقة</h2>
        </div>

        @if($clientProject->files->count())
            <ul class="divide-y divide-gray-100 mb-6">
                @foreach($clientProject->files as $file)
                    <li class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $file->extensionColor() }}">
                                {{ $file->extensionLabel() }}
                            </span>
                            <span class="text-sm text-gray-800 truncate">{{ $file->original_name }}</span>
                            <span class="text-xs text-gray-400 shrink-0">{{ $file->formattedSize() }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0 mr-4">
                            <a href="{{ route('backstage.client-projects.files.download', [$clientProject, $file]) }}"
                                class="text-sm text-blue-600 hover:text-blue-800">تحميل</a>
                            @if($clientProject->status !== \App\Enums\ClientProjectStatus::Cancelled)
                                <button type="button"
                                    @click="deleteUrl = '{{ route('backstage.client-projects.files.destroy', [$clientProject, $file]) }}'; showDeleteModal = true"
                                    class="text-sm text-red-600 hover:text-red-800">حذف</button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-gray-500 mb-4">لا توجد ملفات مرفقة بعد.</p>
        @endif

        @if($clientProject->status !== \App\Enums\ClientProjectStatus::Cancelled)
            <form action="{{ route('backstage.client-projects.files.store', $clientProject) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center gap-3">
                    <input type="file" name="file" accept=".pdf,.docx,.xlsx,.png,.jpg,.jpeg,.gif,.webp" required
                        class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <button type="submit" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                        رفع الملف
                    </button>
                </div>
                @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">PDF، Word، Excel، أو صورة — الحد الأقصى 10 ميجابايت</p>
            </form>
        @endif

        <!-- Delete file confirmation modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showDeleteModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <p class="text-sm text-gray-700">هل أنت متأكد من حذف هذا الملف؟ لا يمكن التراجع عن هذا الإجراء.</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse gap-3">
                        <form :action="deleteUrl" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700">
                                حذف
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">الفواتير</h2>
            <a href="{{ route('backstage.client-projects.invoices.create', $clientProject) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded-md text-sm">
                إضافة فاتورة
            </a>
        </div>

        @if($clientProject->invoices->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الدفع</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clientProject->invoices as $invoice)
                            <tr class="{{ $invoice->isOverdue() ? 'bg-red-50' : '' }}">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">
                                    {{ $invoice->invoice_number ? '#' . $invoice->invoice_number : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($invoice->isOverdue())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            متأخرة
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoiceStatusColors[$invoice->status->color()] }}">
                                            {{ $invoice->status->label() }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm {{ $invoice->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    {{ $invoice->due_date?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->paid_at?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-left text-sm font-medium">
                                    <div class="flex items-center gap-3 justify-end">
                                        <a href="{{ route('backstage.client-projects.invoices.pdf', [$clientProject, $invoice]) }}"
                                           target="_blank"
                                           class="text-gray-600 hover:text-gray-900" title="تصدير PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        @if($invoice->status !== \App\Enums\InvoiceStatus::Paid)
                                            <form action="{{ route('backstage.client-projects.invoices.mark-paid', [$clientProject, $invoice]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 text-xs font-medium" title="تحديد كمدفوعة">
                                                    ✓ مدفوعة
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('backstage.client-projects.invoices.edit', [$clientProject, $invoice]) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-600 mb-1">المجموع:</div>
                    @foreach($clientProject->invoices->groupBy('currency') as $currency => $group)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ $currency }}</span>
                            <span class="font-medium text-gray-900">{{ number_format($group->sum('amount'), 2) }} {{ $currency }}</span>
                        </div>
                    @endforeach
                </div>
                <div>
                    <div class="text-sm text-green-600 mb-1">المدفوع:</div>
                    @php
                        $paidGroups = $clientProject->invoices
                            ->where('status', \App\Enums\InvoiceStatus::Paid)
                            ->groupBy('currency');
                    @endphp
                    @forelse($paidGroups as $currency => $group)
                        <div class="flex justify-between text-sm">
                            <span class="text-green-500">{{ $currency }}</span>
                            <span class="font-medium text-green-600">{{ number_format($group->sum('amount'), 2) }} {{ $currency }}</span>
                        </div>
                    @empty
                        <div class="text-sm text-gray-400">—</div>
                    @endforelse
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">لا توجد فواتير بعد.</p>
        @endif
    </div>
</div>
@endsection
