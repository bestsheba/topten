<button wire:click="openQuickView()" type="button"
    class="whitespace-nowrap w-full bg-primary text-text-primary py-1 px-4 rounded md:rounded-lg font-medium flex items-center justify-center gap-2 hover:bg-secondary transition-colors">
    <span class="text-sm">Order Now</span>
    @if ($spinner)
        <i class="md:!text-3xl las la-spinner animate-spin"></i>
    @else
        <i class="md:!text-3xl las la-shopping-cart"></i>
    @endif
</button>
