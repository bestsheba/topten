@extends('frontend.layouts.user')
@section('title', 'Wallet')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Affiliate Wallet</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white shadow-md rounded-lg p-6 transform transition hover:scale-105 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-sm uppercase tracking-wider">Balance</div>
                        <div class="text-3xl font-extrabold text-primary mt-2">{{ number_format($wallet->balance, 2) }}</div>
                    </div>
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6 transform transition hover:scale-105 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-sm uppercase tracking-wider">Total Earned</div>
                        <div class="text-3xl font-extrabold text-green-600 mt-2">{{ number_format($wallet->total_earned, 2) }}</div>
                    </div>
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6 transform transition hover:scale-105 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-sm uppercase tracking-wider">Total Withdrawn</div>
                        <div class="text-3xl font-extrabold text-red-600 mt-2">{{ number_format($wallet->total_withdrawn, 2) }}</div>
                    </div>
                    <div class="bg-red-100 text-red-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4 border-primary">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Request Withdrawal
                </h2>
                <form action="{{ route('affiliate.withdraw') }}" method="POST" class="space-y-4">
                    @csrf
                    @if($errors->has('withdrawal'))
                        <div class="bg-red-50 border border-red-300 text-red-600 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ $errors->first('withdrawal') }}</span>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <input type="number" min="{{ $settings->affiliate_min_withdrawal_amount ?? 100 }}" step="0.01" 
                               placeholder="Minimum: {{ number_format($settings->affiliate_min_withdrawal_amount ?? 100, 2) }}" name="amount"
                            value="{{ old('amount') }}"
                            class="w-full border-2 @error('amount') border-red-500 @else border-gray-300 @enderror rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <small class="text-gray-500 text-xs mt-1">
                            Minimum withdrawal amount: {{ number_format($settings->affiliate_min_withdrawal_amount ?? 100, 2) }}
                        </small>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Method</label>
                        <input type="text" name="method" 
                            value="{{ old('method') }}"
                            class="w-full border-2 @error('method') border-red-500 @else border-gray-300 @enderror rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Withdrawal Method" />
                        @error('method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Info</label>
                        <input type="text" name="account_info" 
                            value="{{ old('account_info') }}"
                            class="w-full border-2 @error('account_info') border-red-500 @else border-gray-300 @enderror rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Account number or details" />
                        @error('account_info')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full px-6 py-3 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition duration-300 ease-in-out transform hover:scale-105">
                        Submit Withdrawal Request
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4 border-green-500">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Withdrawal History
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="text-left p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $w)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="p-3 border-b">{{ $w->created_at->format('d M, Y') }}</td>
                                    <td class="p-3 border-b font-semibold">{{ number_format($w->amount, 2) }}</td>
                                    <td class="p-3 border-b">{{ $w->method ?: '-' }}</td>
                                    <td class="p-3 border-b">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ match($w->status) {
                                                'pending' => 'bg-yellow-50 text-yellow-600',
                                                'approved' => 'bg-green-50 text-green-600', 
                                                'rejected' => 'bg-red-50 text-red-600', 
                                                default => 'bg-gray-50 text-gray-600'
                                            } }}">
                                            {{ $w->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                        No withdrawals yet.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $withdrawals->links() }}</div>
            </div>
        </div>
    </div>
@endsection
