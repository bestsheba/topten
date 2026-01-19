@extends('frontend.layouts.user')
@section('title', 'Earnings')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Affiliate Earnings</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Orders</div>
                <div class="text-2xl font-bold mt-2">{{ number_format($summary['total_orders']) }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Sales</div>
                <div class="text-2xl font-bold mt-2">{{ number_format($summary['total_sales'], 2) }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Commission ({{ number_format($commissionPercent, 2) }}%)</div>
                <div class="text-2xl font-bold mt-2 text-green-600">{{ number_format($summary['total_commission'], 2) }}</div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Earning History</h2>
                <div class="text-sm text-gray-500">Commission Rate: {{ number_format($commissionPercent, 2) }}%</div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                            <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">#{{ $order->id }}</td>
                                <td class="p-3 border-b">{{ $order->created_at->format('d M, Y') }}</td>
                                <td class="p-3 border-b">{{ $order->customer_name ?? '-' }}</td>
                                <td class="p-3 border-b font-semibold">{{ number_format($order->total, 2) }}</td>
                                <td class="p-3 border-b font-semibold text-green-600">{{ number_format($order->affiliate_commission, 2) }}</td>
                                <td class="p-3 border-b">{!! $order->status_b_value !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No earnings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        </div>
    </div>
@endsection


