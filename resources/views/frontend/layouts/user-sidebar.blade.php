<div class="space-y-1 pl-8 pt-4">
    <details class="group">
        <summary
            class="{{ request()->routeIs('user.notification*') ? 'text-primary' : '' }} relative hover:text-primary font-medium capitalize transition flex justify-between">
            <span class="relative block font-medium capitalize transition">
                <span class="absolute -left-8 -top-1 text-base">
                    <i class="text-2xl las la-user-alt"></i>
                </span>
                Profile
            </span>
            <span class="transition group-open:rotate-180">
                <i class="las la-angle-down ml-2 h-4 w-4"></i>
            </span>
        </summary>
        <a href="{{ route('user.profile') }}"
            class="{{ request()->routeIs('user.profile') ? '!text-primary' : '' }} flex items-center px-2 py-2 hover:text-blue-500">
            Profile information
        </a>
        <a href="{{ route('user.password') }}"
            class="{{ request()->routeIs('user.password') ? '!text-primary' : '' }} flex items-center px-2 py-2 hover:text-blue-500">
            Change password
        </a>
    </details>
</div>
<div class="space-y-1 pl-8 pt-4">
    <a href="{{ route('user.order') }}"
        class="{{ request()->routeIs('user.order*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
        <span class="absolute -left-8 -top-1 text-base">
            <i class="text-2xl las la-shopping-cart"></i>
        </span>
        Orders
    </a>
</div>
<div class="space-y-1 pl-8 pt-4">
    <a href="{{ route('user.wishlist') }}"
        class="{{ request()->routeIs('user.wishlist*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
        <span class="absolute -left-8 -top-1 text-base">
            <i class="text-2xl las la-grin-hearts"></i>
        </span>
        Wishlist
    </a>
</div>
@if (isset($user) && $user?->is_affiliate && $settings->affiliate_feature_enabled)
    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('affiliate.dashboard') }}"
            class="{{ request()->routeIs('affiliate.dashboard') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-file-invoice-dollar"></i>
            </span>
            Affiliate Dashboard
        </a>
    </div>
@endif
