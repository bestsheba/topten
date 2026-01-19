<button type="button" wire:click="openCartDrawer"
    class="{{ $footer ? 'flex justify-center items-center group pt-6 text-text-black hover:text-text-black/50 relative' : 'text-center text-text-primary hover:text-secondary transition relative' }}">
    @if ($footer)
        <div
            class="text-text-primary hover:text-secondary group-hover:-mt-1 absolute top-0 p-1.5 fill-white rounded-full group-hover:text-text-black/50 group-hover:scale-125 group-hover:bg-primary-400 duration-500">
            <i class="las la-shopping-cart text-[2rem] leading-none"></i>
            <div class="absolute text-primary text-sm bg-text-primary rounded-full px-1.5 py-0 top-0 right-0">
                {{ $items_in_user_cart ?? 0 }}
            </div>
        </div>
        <div class="pt-4 mt-1 font-semibold">
            Cart
        </div>
    @else
        <i class="!text-3xl las la-shopping-bag"></i>
        <div class="text-xs leading-3">
            Cart
        </div>
        <div
            class="absolute -right-3 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-text-primary text-primary text-xs">
            {{ $items_in_user_cart ?? 0 }}
        </div>
    @endif
</button>
