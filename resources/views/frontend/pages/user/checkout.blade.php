@extends('frontend.layouts.app')
@section('title', 'Checkout')
@section('content')
    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="{{ route('index') }}" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">
            Checkout
        </p>
    </div>
    <!-- ./breadcrumb -->

    <!-- wrapper -->
    <form class="container grid grid-cols-12 items-start pb-16 pt-4 gap-6" action="{{ route('place.order') }}" method="POST">
        @csrf
        <div class="col-span-12 md:col-span-8 border border-gray-200 p-4 rounded" x-data="{
            paymentMethod: '{{ old('payment_method', $settings->cash_on_delivery_enabled ? 'cash_on_delivery' : ($settings->bkash_enabled || $settings->nagad_enabled || $settings->rocket_enabled || $settings->bank_enabled || $settings->sslcommerz_enabled || $settings->stripe_enabled ? 'online' : '')) }}',
            paymentMethodValue: '{{ old('payment_method_getway', '') }}',
            cashOnDeliveryEnabled: {{ $settings->cash_on_delivery_enabled ? 'true' : 'false' }},
            onlinePaymentEnabled: {{ $settings->bkash_enabled || $settings->nagad_enabled || $settings->rocket_enabled || $settings->bank_enabled || $settings->sslcommerz_enabled || $settings->stripe_enabled ? 'true' : 'false' }},
            init() {
                // Set default payment method based on what's enabled
                if (this.cashOnDeliveryEnabled && !this.onlinePaymentEnabled) {
                    this.paymentMethod = 'cash_on_delivery';
                } else if (!this.cashOnDeliveryEnabled && this.onlinePaymentEnabled) {
                    this.paymentMethod = 'online';
                } else if (this.cashOnDeliveryEnabled && this.onlinePaymentEnabled) {
                    // Both enabled, keep current selection or default to COD
                    if (!this.paymentMethod) {
                        this.paymentMethod = 'cash_on_delivery';
                    }
                }
            }
        }">
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Customer Information
                    </h2>
                    @if ($address)
                        <button onclick="useOldAddress()" type="button"
                            class="px-4 py-1 text-center text-white bg-primary border border-primary rounded hover:bg-transparent hover:text-primary transition">
                            Use Old
                        </button>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="text-gray-600">
                            Name
                            <span class="text-primary">*</span>
                        </label>
                        <input placeholder="Name" type="text" name="name" id="name"
                            class="input-box w-full border-gray-300 rounded-md p-2 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $address?->name) }}">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="phone_number" class="text-gray-600">
                            Phone Number
                            <span class="text-primary">*</span>
                        </label>
                        <input placeholder="Phone Number" type="text" name="phone_number" id="phone_number"
                            class="input-box w-full border-gray-300 rounded-md p-2 @error('phone_number') border-red-500 @enderror"
                            value="{{ old('phone_number', $address?->phone_number) }}">
                        @error('phone_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-full">
                        <label for="address" class="text-gray-600">
                            Full Address
                            <span class="text-primary">*</span>
                        </label>
                        <textarea placeholder="Full Address" type="text" name="address" id="address"
                            class="input-box w-full border-gray-300 rounded-md p-2 @error('address') border-red-500 @enderror">{{ old('address', $address?->address) }}</textarea>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Delivery Area Selection -->
                    @if ($inside_dhaka > 0 || $outside_dhaka > 0)
                        <div class="col-span-full">
                            <label class="text-gray-600">
                                Delivery Area
                                <span class="text-primary">*</span>
                            </label>
                            <div class="flex gap-4 mt-2">
                                @if ($inside_dhaka > 0)
                                    <div class="flex items-center">
                                        <input type="radio" id="inside_dhaka" name="delivery_area" value="inside_dhaka"
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                            {{ old('delivery_area', $inside_dhaka > 0 ? 'inside_dhaka' : 'outside_dhaka') == 'inside_dhaka' ? 'checked' : '' }}>
                                        <label for="inside_dhaka" class="ml-2 block text-sm text-gray-700">
                                            {{ $inside_title }} ({{ showAmount($inside_dhaka) }})
                                        </label>
                                    </div>
                                @endif
                                @if ($outside_dhaka > 0)
                                    <div class="flex items-center">
                                        <input type="radio" id="outside_dhaka" name="delivery_area" value="outside_dhaka"
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                            {{ old('delivery_area', $inside_dhaka > 0 ? 'inside_dhaka' : 'outside_dhaka') == 'outside_dhaka' ? 'checked' : '' }}>
                                        <label for="outside_dhaka" class="ml-2 block text-sm text-gray-700">
                                            {{ $outside_title }} ({{ showAmount($outside_dhaka) }})
                                        </label>
                                    </div>
                                @endif
                                @if ($sub_area > 0)
                                    <div class="flex items-center">
                                        <input type="radio" id="sub_area" name="delivery_area" value="sub_area"
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                            {{ old('delivery_area') == 'sub_area' ? 'checked' : '' }}>
                                        <label for="sub_area" class="ml-2 block text-sm text-gray-700">
                                            {{ $sub_area_title }} ({{ showAmount($sub_area) }})
                                        </label>
                                    </div>
                                @endif
                            </div>
                            @error('delivery_area')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="delivery_area" value="no_delivery">
                    @endif

                    <!-- Payment Method Selection -->
                    @if (
                        $settings->cash_on_delivery_enabled ||
                            $settings->bkash_enabled ||
                            $settings->nagad_enabled ||
                            $settings->rocket_enabled ||
                            $settings->bank_enabled ||
                            $settings->sslcommerz_enabled)
                        <div class="col-span-full">
                            <label for="payment_method" class="text-gray-600">
                                Payment Method
                                <span class="text-primary">*</span>
                            </label>
                            <div>
                                <input :value="paymentMethod" name="payment_method" type="text" class="hidden peer">
                                <label for="payment_method"
                                    class="inline-flex items-center rounded-md cursor-pointer text-gray-100">
                                    <input id="payment_method" @checked(old('payment_method') == 'online') :value="paymentMethod"
                                        type="checkbox" class="hidden peer">
                                    @if ($settings->cash_on_delivery_enabled)
                                        <span @click="paymentMethod = 'cash_on_delivery'"
                                            class="text-xs md:text-base whitespace-nowrap px-4 py-2 {{ $settings->bkash_number || $settings->nagad_number || $settings->rocket_number || $settings->bank_account_number ? 'rounded-l-md' : 'rounded-md' }} bg-primary peer-checked:bg-gray-700 cursor-pointer">
                                            Cash On Delivery
                                        </span>
                                    @endif
                                    @if (
                                        $settings->bkash_number ||
                                            $settings->nagad_number ||
                                            $settings->rocket_number ||
                                            $settings->bank_account_number ||
                                            $settings->sslcommerz_enabled ||
                                            $settings->stripe_enabled)
                                        <span @click="paymentMethod = 'online'"
                                            class="text-[10px] sm:text-xs md:text-base whitespace-nowrap px-4 py-2 {{ $settings->cash_on_delivery_enabled ? 'rounded-r-md' : 'rounded-md' }} bg-gray-700 peer-checked:bg-primary cursor-pointer">
                                            Online Payment
                                        </span>
                                    @endif
                                </label>
                            </div>
                            @error('payment_method')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Payment Gateway Selection -->
                    @if (
                        $settings->bkash_enabled ||
                            $settings->nagad_enabled ||
                            $settings->rocket_enabled ||
                            $settings->bank_enabled ||
                            $settings->sslcommerz_enabled ||
                            $settings->stripe_enabled)
                        <div class="col-span-full" x-show="paymentMethod == 'online'" x-cloak>
                            <label for="payment_method_gateway" class="text-gray-600">
                                Payment Gateway
                                <span class="text-primary">*</span>
                            </label>
                            <select name="payment_method_gateway" id="payment_method_gateway"
                                class="input-box w-full border-gray-300 rounded-md p-2 @error('payment_method_gateway') border-red-500 @enderror"
                                x-model="paymentMethodValue">
                                <option value="">Select Payment Method</option>
                                @if ($settings->sslcommerz_enabled)
                                    <option @selected(old('sslcommerz_enabled') == 'sslcommerz') value="sslcommerz">
                                        SSLCommerz
                                    </option>
                                @endif
                                @if ($settings->bkash_enabled)
                                    <option @selected(old('payment_method_gateway') == 'bKash') value="bKash">
                                        bKash
                                    </option>
                                @endif
                                @if ($settings->nagad_enabled)
                                    <option @selected(old('payment_method_gateway') == 'Nagad') value="Nagad">
                                        Nagad
                                    </option>
                                @endif
                                @if ($settings->rocket_enabled)
                                    <option @selected(old('payment_method_gateway') == 'Rocket') value="Rocket">
                                        Rocket
                                    </option>
                                @endif
                                @if ($settings->bank_enabled)
                                    <option @selected(old('payment_method_gateway') == 'Bank Account') value="Bank Account">
                                        Bank Account
                                    </option>
                                @endif
                                @if ($settings->stripe_enabled)
                                    <option @selected(old('payment_method_gateway') == 'stripe') value="stripe">
                                        Stripe
                                    </option>
                                @endif
                            </select>
                            @error('payment_method_gateway')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Payment Gateway Details -->
                        @if ($settings->bkash_enabled)
                            <div class="col-span-full" x-show="paymentMethodValue == 'bKash' && paymentMethod == 'online'"
                                x-cloak>
                                <x-frontend.checkout.number-copy :value="$settings->bkash_number" :note="$settings->bkash_number_note" />
                            </div>
                        @endif

                        @if ($settings->nagad_enabled)
                            <div class="col-span-full" x-show="paymentMethodValue == 'Nagad' && paymentMethod == 'online'"
                                x-cloak>
                                <x-frontend.checkout.number-copy :value="$settings->nagad_number" :note="$settings->nagad_number_note" />
                            </div>
                        @endif

                        @if ($settings->rocket_enabled)
                            <div class="col-span-full"
                                x-show="paymentMethodValue == 'Rocket' && paymentMethod == 'online'" x-cloak>
                                <x-frontend.checkout.number-copy :value="$settings->rocket_number" :note="$settings->rocket_number_note" />
                            </div>
                        @endif

                        @if ($settings->bank_enabled)
                            <div class="col-span-full"
                                x-show="paymentMethodValue == 'Bank Account' && paymentMethod == 'online'" x-cloak>
                                <x-frontend.checkout.number-copy :value="$settings->bank_account_number" :note="$settings->bank_account_number_note" />
                            </div>
                        @endif

                        <!-- Transaction ID -->
                        <div class="col-span-full"
                            x-show="paymentMethod == 'online' && ['bKash', 'Nagad', 'Rocket', 'Bank Account'].includes(paymentMethodValue)"
                            x-cloak>
                            <label for="transaction_id" class="text-gray-600">
                                <span x-text="paymentMethodValue"></span> Transaction ID
                                <span class="text-primary">*</span>
                            </label>
                            <input :placeholder="paymentMethodValue + ' Transaction ID'" type="text"
                                name="transaction_id" id="transaction_id"
                                class="input-box w-full border-gray-300 rounded-md p-2 @error('transaction_id') border-red-500 @enderror"
                                value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-span-12 md:col-span-4 border border-gray-200 p-4 rounded">
            <h4 class="text-gray-800 text-lg mb-4 font-medium uppercase">
                Order Summary
            </h4>
            <div class="space-y-2">
                @foreach ($carts as $item)
                    <div class="border-b">
                        <div class="flex justify-between mb-2">
                            <div>
                                <h5 class="text-gray-800 font-medium">
                                    {{ $item['product']['name'] }}
                                    @if (isset($item['variation']) && $item['variation'])
                                        <div class="text-sm text-gray-500">
                                            {{ $item['variation']->attributeValues->map(fn($val) => $val->attribute->name . ': ' . $val->value)->join(', ') }}
                                        </div>
                                    @endif
                                </h5>
                            </div>
                            <p class="text-gray-800 font-medium">
                                {{ $item['quantity'] }}
                                <span class="text-gray-500">x</span>
                                @php
                                    if (isset($item['variation']) && $item['variation']) {
                                        $price = $item['variation']->price ?? 0;
                                        $discountType = null;
                                        $discountValue = 0;
                                    } else {
                                        $price = $item['product']['price'] ?? 0;
                                        $discountType = $item['product']['discount_type'] ?? null;
                                        $discountValue = (float) ($item['product']['discount'] ?? 0);
                                    }

                                    echo showAmount(calculateDiscountedPrice($price, $discountType, $discountValue));
                                @endphp
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Coupon Section -->
            <div class="border-b border-gray-200 py-4">
                <h5 class="text-gray-800 font-medium mb-3">Coupon Code</h5>

                @if (session()->has('checkout_coupon'))
                    @php
                        $appliedCoupon = \App\Models\Coupon::where('code', session('checkout_coupon'))->first();
                    @endphp
                    @if ($appliedCoupon)
                        <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-green-800 font-medium text-sm">{{ $appliedCoupon->code }}</p>
                                    <p class="text-green-600 text-xs">
                                        @if ($appliedCoupon->discount_type == 'amount')
                                            {{ showAmount($appliedCoupon->discount) }} off
                                        @else
                                            {{ $appliedCoupon->discount }}% off
                                        @endif
                                    </p>
                                </div>
                                <button type="button" onclick="removeCoupon()"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex gap-2">
                        <input type="text" id="coupon_code_input" placeholder="Enter coupon code"
                            class="flex-1 border border-gray-300 rounded-md p-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('coupon_code') border-red-500 @enderror"
                            value="{{ old('coupon_code') }}">
                        <button type="button" onclick="applyCoupon()"
                            class="px-3 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition text-sm font-medium">
                            Apply
                        </button>
                    </div>
                    @error('coupon_code')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                <p class="font-bold">Subtotal</p>
                <p>{{ showAmount($subtotal) }}</p>
            </div>

            @if ($inside_dhaka > 0 || $outside_dhaka > 0)
                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                    <p class="font-bold">Delivery Charge</p>
                    <p>
                        <span class="text-sm font-semibold text-black dark:text-white"
                            style="font-family: Arial, sans-serif;">
                            ৳
                        </span><span
                            id="deliveryCharge">{{ number_format(old('delivery_area', $inside_dhaka > 0 ? 'inside_dhaka' : 'outside_dhaka') == 'inside_dhaka' ? $inside_dhaka : $outside_dhaka, 2) }}</span>
                    </p>
                </div>
            @endif

            @if ($discount > 0)
                <div class="flex justify-between border-b border-gray-200 mt-1 text-gray-800 font-medium py-3 uppercas">
                    <p class="font-bold">Discount</p>
                    <p>{{ showAmount($discount) }}</p>
                </div>
            @endif

            <div class="flex justify-between text-gray-800 font-medium py-3 uppercas">
                <p class="font-bold">Total</p>
                <p>
                    {{ currencySymbol() }}<span id="totalAmount">
                        {{ number_format($subtotal + ($inside_dhaka > 0 || $outside_dhaka > 0 ? (old('delivery_area', $inside_dhaka > 0 ? 'inside_dhaka' : 'outside_dhaka') == 'inside_dhaka' ? $inside_dhaka : $outside_dhaka) : 0) - $discount, 2) }}
                    </span>
                </p>
            </div>

            <div x-data="{ isChecked: false }">
                <div class="flex items-center mb-4 mt-2">
                    <input type="checkbox" name="aggrement" id="aggrement"
                        class="text-primary focus:ring-0 rounded-sm cursor-pointer w-4 h-4" x-model="isChecked">
                    <label for="aggrement" class="text-gray-600 ml-3 cursor-pointer text-sm">
                        I agree to the <a href="#" class="text-primary">terms & conditions</a>
                    </label>
                </div>
                <button type="submit"
                    class="block w-full py-3 px-4 text-center text-white bg-primary border border-primary rounded-md hover:bg-transparent hover:text-primary transition font-medium"
                    :disabled="!isChecked" :class="{ 'opacity-50 cursor-not-allowed': !isChecked }">
                    Place order
                </button>
            </div>
        </div>
    </form>
    <!-- ./wrapper -->
@endsection

@push('scripts')
    <script>
        function useOldAddress() {
            document.getElementById('name').value = '{{ $address?->name }}';
            document.getElementById('phone_number').value = '{{ $address?->phone_number }}';
            document.getElementById('address').value = '{{ $address?->address }}';
        }

        function applyCoupon() {
            const couponCode = document.getElementById('coupon_code_input').value;
            if (!couponCode.trim()) {
                alert('Please enter a coupon code');
                return;
            }

            // Create a form dynamically and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('apply.coupon.code') }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add coupon code
            const couponInput = document.createElement('input');
            couponInput.type = 'hidden';
            couponInput.name = 'coupon_code';
            couponInput.value = couponCode;
            form.appendChild(couponInput);

            // Append to body and submit
            document.body.appendChild(form);
            form.submit();
        }

        function removeCoupon() {
            // Create a form dynamically and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('remove.coupon') }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Append to body and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Update total when delivery area changes
        document.addEventListener('DOMContentLoaded', function() {
            const deliveryRadios = document.querySelectorAll('input[name="delivery_area"]');
            deliveryRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // This will trigger Alpine.js to update the displayed total
                });
            });
        });
    </script>
    <!-- JavaScript Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const insideDhakaCharge = {{ $inside_dhaka }};
            const outsideDhakaCharge = {{ $outside_dhaka }};
            const subAreaCharge = {{ $sub_area ?? 0 }};
            const subtotal = {{ $subtotal }};
            const discount = {{ $discount }};

            const deliveryInputs = document.querySelectorAll('input[name="delivery_area"]');
            const deliveryChargeSpan = document.getElementById('deliveryCharge');
            const totalSpan = document.getElementById('totalAmount');

            function formatNumber(num) {
                return num.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateTotals() {
                let selectedArea = document.querySelector('input[name="delivery_area"]:checked');
                let deliveryCharge = 0;

                if (selectedArea) {
                    if (selectedArea.value === 'inside_dhaka') {
                        deliveryCharge = insideDhakaCharge;
                    } else if (selectedArea.value === 'sub_area') {
                        deliveryCharge = subAreaCharge;
                    } else {
                        deliveryCharge = outsideDhakaCharge;
                    }
                }

                let total = subtotal + deliveryCharge - discount;

                if (deliveryChargeSpan) {
                    deliveryChargeSpan.textContent = formatNumber(deliveryCharge);
                }
                totalSpan.textContent = formatNumber(total);
            }

            // Initial calculation
            updateTotals();

            // Listen for changes
            deliveryInputs.forEach(input => {
                input.addEventListener('change', updateTotals);
            });
        });
    </script>
    @if ($incompleteOrderId)
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const ids = ["name", "phone_number", "address", "delivery_area"];

                ids.forEach(id => {
                    const element = document.getElementById(id) || document.querySelector(
                        `input[name="${id}"]`);
                    if (element) {
                        element.addEventListener("change", (event) => {
                            let value;

                            if (element.type === "checkbox") {
                                value = element.checked ? 1 : 0;
                            } else if (element.type === "radio") {
                                if (element.checked) {
                                    value = element.value;
                                } else {
                                    return; // Skip if radio is not checked
                                }
                            } else {
                                value = element.value;
                            }

                            axios.post(
                                    "{{ route('push.incomplete.order.data', $incompleteOrderId) }}", {
                                        _token: '{{ csrf_token() }}',
                                        field: id,
                                        value: value,
                                    })
                                .then(response => {
                                    // console.log("Data saved successfully.");
                                })
                                .catch(error => {
                                    console.error("Error saving data:", error);
                                });
                        });
                    }
                });
            });
        </script>
    @endif
@endpush
