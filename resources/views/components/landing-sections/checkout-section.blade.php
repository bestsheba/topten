<!-- Checkout Section -->
<section class="py-20 px-4 bg-gradient-to-b from-white via-gray-50 to-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl opacity-20" style="background-color: var(--primary-bg);">
    </div>
    <div
        class="absolute bottom-0 right-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl opacity-20" style="background-color: var(--secondary-bg);">
    </div>

    <div class="max-w-3xl mx-auto relative z-10">
        <div class="bg-gradient-red py-8 px-6 rounded-t-3xl shadow-2xl relative overflow-hidden" style="color: var(--primary-text);">
            <div class="absolute inset-0 opacity-10 bg-gradient-to-r from-transparent via-white to-transparent"></div>
            <h2 class="text-3xl md:text-4xl font-black text-center relative z-10 animate-slide-in-up">
                {{ $section['data']['title'] ?? '📝 অর্ডার করতে নিচের ফর্মটি পূরণ করুন' }}
            </h2>
        </div>

        <div class="bg-white rounded-b-3xl shadow-2xl p-6 md:p-10">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('landing.page.order', $landing_page->url ?? request()->segment(2)) }}" class="space-y-8">
                @csrf
                <!-- Billing Details -->
                <div class="mb-10 animate-slide-in-up" style="animation-delay: 0.1s;">
                    <h3 class="text-2xl font-black text-gradient mb-6">📋 বিলিং বিস্তারিত</h3>

                    <div class="mb-6">
                        <label class="block text-lg font-bold text-gray-700 mb-2">আপনার নাম <span
                                style="color: var(--primary-bg);">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="যেমন: রহিম আহমেদ" required
                            class="w-full px-6 py-3 border-2 border-gray-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                            style="--focus-ring: var(--primary-bg); --hover-border: var(--primary-bg);">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-lg font-bold text-gray-700 mb-2">মোবাইল নাম্বার <span
                                style="color: var(--primary-bg);">*</span></label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="যেমন: 01700000000" required
                            class="w-full px-6 py-3 border-2 border-gray-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                            style="--focus-ring: var(--primary-bg); --hover-border: var(--primary-bg);">
                        @error('phone_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-lg font-bold text-gray-700 mb-2">সম্পূর্ণ ঠিকানা <span
                                style="color: var(--primary-bg);">*</span></label>
                        <textarea name="address" placeholder="গ্রাম, উপজেলা, জেলা" rows="3" required
                            class="w-full px-6 py-3 border-2 border-gray-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all resize-none"
                            style="--focus-ring: var(--primary-bg); --hover-border: var(--primary-bg);">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t-4 border-gray-200 pt-8 mb-8 animate-slide-in-up" style="animation-delay: 0.2s;">
                    <h3 class="text-2xl font-black text-gradient mb-6">🛍️ আপনার অর্ডার</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full mb-6">
                            <thead class="text-white" style="background: linear-gradient(to right, var(--primary-bg), var(--primary-bg));">
                                <tr>
                                    <th class="px-4 py-4 text-left text-lg font-bold">পণ্য</th>
                                    <th class="px-4 py-4 text-right text-lg font-bold">মূল্য</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b-2 border-gray-200 hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 text-lg text-gray-700 font-bold">
                                        {{ $section['data']['product_name'] ?? 'Product' }} ×1</td>
                                    <td class="px-4 py-4 text-right text-lg font-bold" style="color: var(--primary-bg);">
                                        {{ $section['data']['product_price'] ?? '1,499.00' }}৳</td>
                                </tr>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50">
                                    <td class="px-4 py-4 text-lg font-black text-gray-800">ডেলিভারি চার্জ</td>
                                    <td class="px-4 py-4 text-right text-lg font-bold text-gray-800">
                                        {{ $section['data']['delivery_charge'] ?? 'বিনামূল্যে ✅' }}</td>
                                </tr>
                                <tr style="background: linear-gradient(to right, var(--secondary-bg), var(--secondary-bg));">
                                    <td class="px-4 py-5 text-xl font-black" style="color: var(--secondary-text);">মোট</td>
                                    <td class="px-4 py-5 text-right text-2xl font-black" style="color: var(--secondary-text);">
                                        {{ $section['data']['product_price'] ?? '1,499.00' }}৳</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="border-t-4 border-gray-200 pt-8 mb-8 animate-slide-in-up" style="animation-delay: 0.3s;">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">💳 পেমেন্ট পদ্ধতি</h3>

                    <div
                        class="flex items-start gap-4 p-5 border-3 border-green-500 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 hover:border-green-600 hover:shadow-lg transition-all cursor-pointer group">
                        <div class="flex items-center mt-1">
                            <input type="radio" id="cash-delivery" name="payment_method" value="cash_on_delivery" checked
                                class="w-5 h-5 accent-green-600">
                        </div>
                        <div class="flex-1">
                            <label for="cash-delivery"
                                class="text-lg font-bold text-gray-900 cursor-pointer group-hover:text-green-600 transition">
                                ✅ ক্যাশ অন ডেলিভারি (সুপারিশকৃত)
                            </label>
                            <p class="text-gray-600 mt-1">ডেলিভারির সময় নগদ পরিশোধ করুন - কোনো ঝামেলা নেই।</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                        🔒 আপনার ব্যক্তিগত তথ্য সম্পূর্ণ নিরাপদ এবং গোপনীয় থাকবে।
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="text-center animate-slide-in-up" style="animation-delay: 0.4s;">
                    <button type="submit"
                        class="w-full font-black text-2xl py-5 px-6 rounded-lg shadow-2xl transform hover:scale-105 transition-all duration-300 btn-glow animate-glow"
                        style="background-color: var(--primary-bg); color: var(--primary-text);">
                        🎉 এখনই অর্ডার করুন - {{ $section['data']['product_price'] ?? '1,499.00' }}৳
                    </button>
                    <p class="text-sm text-gray-600 mt-4">
                        অর্ডার করে যান - এডভান্স নেই, কোনো লুকানো চার্জ নেই!
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
