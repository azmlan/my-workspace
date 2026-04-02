@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">لوحة التحكم</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي العملاء</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Client Projects -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <x-heroicon-o-clipboard-document-check class="w-6 h-6" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">المشاريع النشطة</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($activeClientProjects) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Unpaid Invoices -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <x-heroicon-o-currency-dollar class="w-6 h-6" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">المبلغ غير المدفوع (ر.س)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalUnpaidAmount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Overdue Projects -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ $overdueProjects > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">المشاريع المتأخرة</p>
                    <p class="text-2xl font-semibold {{ $overdueProjects > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($overdueProjects) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Customers -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">أحدث العملاء</h2>
            </div>
            <div class="p-6">
                @if($recentCustomers->isEmpty())
                    <p class="text-gray-500 text-sm">لا يوجد عملاء بعد.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشاريع</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإضافة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentCustomers as $customer)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('dashboard.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $customer->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $customer->client_projects_count }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $customer->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Projects by Status -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">المشاريع حسب الحالة</h2>
            </div>
            <div class="p-6">
                @if($totalProjects === 0)
                    <p class="text-gray-500 text-sm">لا يوجد مشاريع بعد.</p>
                @else
                    <div class="space-y-4">
                        @foreach($projectsByStatus as $status => $data)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">{{ $data['label'] }}</span>
                                    <span class="text-gray-500">{{ $data['count'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $percentage = $totalProjects > 0 ? ($data['count'] / $totalProjects) * 100 : 0;
                                        $colorClasses = match($data['color']) {
                                            'gray' => 'bg-gray-400',
                                            'blue' => 'bg-blue-500',
                                            'yellow' => 'bg-yellow-400',
                                            'green' => 'bg-green-500',
                                            'red' => 'bg-red-500',
                                            default => 'bg-gray-400',
                                        };
                                    @endphp
                                    <div class="{{ $colorClasses }} h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-700">إجمالي المشاريع</span>
                            <span class="font-semibold text-gray-900">{{ $totalProjects }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
