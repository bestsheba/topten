@auth
    <div class="px-4 py-3 shadow flex items-center gap-4">
        <div class="flex-shrink-0">
            <img src="{{ $user?->avatar_url ?? makeAvatar('User') }}" alt="{{ $user?->name ?? 'User' }}"
                class="rounded-full w-14 h-14 border border-gray-200 p-1 object-cover">
        </div>
        <div class="flex-grow">
            <p class="text-gray-600">Hello,</p>
            <h4 class="text-gray-800 font-medium">
                {{ $user?->name ?? 'User' }}
            </h4>
        </div>
    </div>
@endauth

<div class="mt-6 bg-white shadow rounded p-4 divide-y divide-gray-200 space-y-4 text-gray-600">
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
        <a href="{{ route('user.notification') }}"
            class="{{ request()->routeIs('user.notification*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-bell"></i>
            </span>
            Notification
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.order') }}"
            class="{{ request()->routeIs('user.order*') && !request()->routeIs('user.order.track') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-shopping-cart"></i>
            </span>
            Orders
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.order.track') }}"
            class="{{ request()->routeIs('user.order.track*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-truck"></i>
            </span>
            Track Order
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

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.address') }}"
            class="{{ request()->routeIs('user.address*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-home"></i>
            </span>
            Manage Address
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.transaction') }}"
            class="{{ request()->routeIs('user.transaction*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-file-invoice-dollar"></i>
            </span>
            Transection History
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('shop', ['offer' => 1]) }}"
            class="relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-bullhorn"></i>
            </span>
            Special Offer
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.referral') }}"
            class="{{ request()->routeIs('user.referral*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-user-tie"></i>
            </span>
            Reffer & Earn
        </a>
    </div>
    
    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.rate-us') }}"
            class="{{ request()->routeIs('user.rate-us*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-star"></i>
            </span>
            Rate us
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('custom.page', 'terms-and-conditions') }}"
            class="{{ request()->routeIs('custom.page', 'terms_and_conditions') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-crutch"></i>
            </span>
            Terms & Conditions
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('custom.page', 'privacy-policy') }}"
            class="{{ request()->routeIs('user.wishlist*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-lock"></i>
            </span>
            Privacy Policy
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('custom.page', 'return-and-refund-policy') }}"
            class="{{ request()->routeIs('custom.page', 'return-and-refund-policy') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-fast-backward"></i>
            </span>
            Return & Refund Policy
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('custom.page', 'faq') }}"
            class="{{ request()->routeIs('custom.page', 'faq') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-2xl las la-pen"></i>
            </span>
            FAQ
        </a>
    </div>

    <div class="space-y-1 pl-8 pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="relative hover:text-primary block font-medium capitalize transition">
                <span class="absolute -left-8 -top-1 text-base">
                    <i class="text-2xl las la-sign-out-alt"></i>
                </span>
                Logout
            </button>
        </form>
    </div>

</div>
