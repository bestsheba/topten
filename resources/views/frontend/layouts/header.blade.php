<!-- Navbar -->
<div class="bg-primary">
    <div class="flex items-center justify-left md:pl-12 py-5 gap-5 no-scrollbar overflow-x-scroll">
        @foreach ($header_categories as $header_category)
            <div class="whitespace-nowrap relative inline-flex text-left" x-data="{ open: false, dropdownTop: 0, dropdownLeft: 0 }"
                @click.away="open = false">
                <button
                    @click="open = !open; if(open) { dropdownTop = $el.getBoundingClientRect().bottom; dropdownLeft = $el.getBoundingClientRect().left }"
                    class="w-full text-white py-2 px-4 rounded flex gap-2 items-center justify-between focus:outline-none">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-full group-hover:text-white group-hover:bg-primary-400 overflow-hidden">
                            <img class="w-8 h-8 bg-white" src="{{ $header_category->picture_url }}"
                                alt="{{ $header_category->name }}">
                        </div>
                        <div class="text-white font-semibold">
                            {{ $header_category->name }}
                        </div>
                    </div>
                    <i class="las la-angle-down ml-2 h-4 w-4"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="fixed z-50 shadow-lg bg-white w-60 rounded border border-gray-200"
                    :style="{ top: `${dropdownTop}px`, left: `${dropdownLeft}px` }" x-cloak>
                    <div class="py-1 text-gray-700" role="menu" aria-orientation="vertical"
                        aria-labelledby="options-menu">
                        @forelse ($header_category->subCategories as $sub_category)
                            <a href="{{ route('shop', ['sub_categories' => [$sub_category->id]]) }}"
                                class="truncate block px-4 py-1 text-sm rounded-sm text-black hover:text-white hover:bg-primary"
                                role="menuitem">
                                {{ $sub_category->name }}
                            </a>
                        @empty
                            <a href="javascript:void(0)"
                                class="block px-4 py-1 text-sm rounded-sm text-black hover:text-white hover:bg-primary"
                                role="menuitem">
                                No sub category found...
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- ./navbar -->
