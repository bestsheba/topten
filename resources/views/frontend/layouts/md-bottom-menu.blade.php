<div class="fixed bottom-0 border-t inset-shadow-2xs w-full z-20 lg:hidden">
    <nav class="grid grid-cols-5 justify-center items-center pb-1.5 pt-1 bg-primary text-text-primary hover:text-secondary text-xs">
        <a href="/" class="flex justify-center items-center group pt-6 text-text-primary hover:text-secondary relative">
            <div class="group-hover:-mt-1 absolute top-0 p-1.5 fill-white border-2 border-white border-opacity-0 rounded-full  group-hover:scale-125 group-hover:bg-primary-400 duration-500">
                <i class="las la-home text-[2rem] leading-none"></i>
            </div>
            <div class="pt-4 mt-1 font-semibold">Home</div>
        </a>
        <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $settings->whatsapp_number }}" class="flex justify-center items-center group pt-6 text-text-primary hover:text-secondary relative">
            <div class="group-hover:-mt-1 absolute top-0 p-1.5 fill-white border-2 border-white border-opacity-0 rounded-full group-hover:scale-125 group-hover:bg-primary-400 duration-500">
                <i class="lab la-whatsapp text-[2rem] leading-none"></i>
            </div>
            <div class="pt-4 mt-1 font-semibold">Chat</div>
        </a>
        <a href="{{ route('affiliate.register') }}" class="flex justify-center items-center group pt-6 text-text-primary hover:text-secondary relative">
            <div class="group-hover:-mt-1 absolute top-0 p-1.5 fill-white border-2 border-white border-opacity-0 rounded-full group-hover:scale-125 group-hover:bg-primary-400 duration-500">
                <i class="las la-user-friends text-[2rem] leading-none"></i>
            </div>
            <div class="pt-4 mt-1 font-semibold">Affiliate</div>
        </a>
        {{-- <a href="{{ route('shop') }}" class="flex justify-center items-center group pt-6 text-text-primary hover:text-secondary relative">
            <div class="text-black group-hover:-mt-1 absolute top-0 p-1.5 fill-white border-2 border-white border-opacity-0 rounded-full group-hover:text-white group-hover:scale-125 group-hover:bg-primary-400 duration-500">
                <i class="las la-cart-plus text-[2rem] leading-none"></i>
            </div>
            <div class="pt-4 mt-mt-1 font-semibold">Shop</div>
        </a> --}}
        @livewire('cart-icon', ['footer' => true])
        <a href="{{ route('user.profile') }}" class="flex justify-center items-center group pt-6 text-text-primary hover:text-secondary relative">
            <div class="group-hover:-mt-1 absolute top-0 p-1.5 fill-white border-2 border-white border-opacity-0 rounded-full group-hover:scale-125 group-hover:bg-primary-400 duration-500">
                <i class="las la-user text-[2rem] leading-none"></i>
            </div>
            <div class="pt-4 mt-1 font-semibold">Account</div>
        </a>
    </nav>
</div>
