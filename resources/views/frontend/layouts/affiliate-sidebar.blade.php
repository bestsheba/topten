<div class="space-y-1 pl-8 pt-4">
    <a href="{{ route('affiliate.wallet') }}"
        class="{{ request()->routeIs('affiliate.wallet*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
        <span class="absolute -left-8 -top-1 text-base">
            <i class="text-2xl las la-wallet"></i>
        </span>
        Wallet
    </a>
</div>
<div class="space-y-1 pl-8 pt-4">
    <a href="{{ route('affiliate.earnings') }}"
        class="{{ request()->routeIs('affiliate.earnings*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
        <span class="absolute -left-8 -top-1 text-base">
            <i class="text-2xl las la-coins"></i>
        </span>
        Earnings
    </a>
</div>