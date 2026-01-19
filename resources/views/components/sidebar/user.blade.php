<a href="{{ route('user.dashboard') }}">
    <div
        class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300 category-highlight {{ request()->routeIs('user.dashboard') ? 'active transform translate-x-[5px]' : '' }}">
        <div class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
                <span class="flex items-center">
                    <i class="fa-solid fa-gauge category-icon mr-2"></i>
                    <span
                        class="font-semibold {{ request()->routeIs('user.dashboard') ? 'text-primary' : '' }}">Dashboard</span>
                </span>
            </summary>
        </div>
    </div>
</a>

<div
    class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300 {{ request()->routeIs('user.profile') || request()->routeIs('user.password') ? 'active transform translate-x-[5px]' : '' }}">
    <details class="group"
        {{ request()->routeIs('user.profile') || request()->routeIs('user.password') ? 'open' : '' }}>
        <summary
            class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
            <span class="flex items-center">
                <i class="fa-solid fa-user category-icon mr-2"></i>
                <span class="font-semibold">
                    Profile
                </span>
            </span>
            <span class="transition duration-300 group-open:rotate-180 bg-primary/10 rounded-full p-1.5 text-primary">
                <i class="las la-angle-down"></i>
            </span>
        </summary>

        <div class="pl-4 pr-2 pb-2 pt-1 space-y-1 border-t border-gray-100 mt-1">
            <a href="{{ route('user.profile') }}"
                class="flex items-center py-2 px-3 rounded-md subcategory-item hover:text-primary group">
                <i class="las la-angle-right text-primary/70 mr-2 group-hover:ml-1 transition-all"></i>
                <div class="text-gray-700 group-hover:text-primary transition-colors duration-200">
                    Profile information
                </div>
            </a>
            <a href="{{ route('user.password') }}"
                class="flex items-center py-2 px-3 rounded-md subcategory-item hover:text-primary group">
                <i class="las la-angle-right text-primary/70 mr-2 group-hover:ml-1 transition-all"></i>
                <div class="text-gray-700 group-hover:text-primary transition-colors duration-200">
                    Change password
                </div>
            </a>
        </div>
    </details>
</div>

<a href="{{ route('user.order') }}">
    <div
        class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300 {{ request()->routeIs('user.order') ? 'active transform translate-x-[5px]' : '' }}">
        <div class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
                <span class="flex items-center">
                    <i class="fa-solid fa-list-check category-icon mr-2"></i>
                    <span
                        class="font-semibold {{ request()->routeIs('user.order') ? 'text-primary' : '' }}">Orders</span>
                </span>
            </summary>
        </div>
    </div>
</a>

<a href="{{ route('user.wishlist') }}">
    <div
        class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300 {{ request()->routeIs('user.wishlist') ? 'active transform translate-x-[5px]' : '' }}">
        <div class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
                <span class="flex items-center">
                    <i class="fa-solid fa-heart category-icon mr-2"></i>
                    <span
                        class="font-semibold {{ request()->routeIs('user.wishlist') ? 'text-primary' : '' }}">Wishlist</span>
                </span>
            </summary>
        </div>
    </div>
</a>

<a href="{{ route('user.transaction') }}">
    <div
        class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300 {{ request()->routeIs('user.transaction') ? 'active transform translate-x-[5px]' : '' }}">
        <div class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
                <span class="flex items-center">
                    <i class="fa-solid fa-file-invoice category-icon mr-2"></i>
                    <span
                        class="font-semibold {{ request()->routeIs('user.transaction') ? 'text-primary' : '' }}">Transaction
                        History</span>
                </span>
            </summary>
        </div>
    </div>
</a>

<a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit()">
    <div class="mb-3 rounded-lg overflow-hidden bg-white hover:bg-gray-50 category-item transition duration-300">
        <div class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none p-3 rounded-md hover:shadow-sm">
                <span class="flex items-center">
                    <i class="fa-solid fa-right-from-bracket category-icon mr-2"></i>
                    <span class="font-semibold">Logout</span>
                </span>
            </summary>
        </div>
    </div>
    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
    </form>
</a>
