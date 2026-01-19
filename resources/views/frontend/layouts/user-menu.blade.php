<div class="space-y-4 text-gray-600">
    <div class="space-y-1 pl-8 pt-4">
        <a href="{{ route('user.dashboard') }}"
            class="{{ request()->routeIs('user.dashboard*') ? 'text-primary' : '' }} relative hover:text-primary block font-medium capitalize transition">
            <span class="absolute -left-8 -top-1 text-base">
                <i class="text-base mt-2 fa-solid fa-gauge"></i>
            </span>
            Dashboard
        </a>
    </div>

    @if (request()->routeIs('affiliate.*'))
        @include('frontend.layouts.affiliate-sidebar')
    @else
        @include('frontend.layouts.user-sidebar')
    @endif

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
