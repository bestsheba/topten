<div class="sticky top-0 z-50 border-b-2 shadow bg-primary py-2 lg:py-0" x-data="{ mobileMenuIsOpen: false, activeMobileCategory: null, activeMobileSubCategory: null }"
    @click.away="mobileMenuIsOpen = false; activeMobileCategory = null; activeMobileSubCategory = null">
    <div class="container px-3 md:px-6 flex items-center justify-between md:py-1 gap-3">
        <div class="">
            <nav class="relative flex justify-between items-center mr-4 lg:mr-0">
                <button class="navbar-burger flex items-center text-text-primary hover:text-secondary">
                    <i class="las la-bars text-2xl leading-none"></i>
                </button>
            </nav>
            <div class="navbar-menu relative z-50 hidden">
                <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-25"></div>
                <nav
                    class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-0 px-0 bg-white border-r overflow-y-auto mobile-category-sidebar">
                    <div class="p-5 sidebar-header text-white relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" width="80" height="80">
                                <path d="M20 20h20v20H20zm20 0h20v20H40zm0 20h20v20H40zm-20 0h20v20H20z"
                                    fill="currentColor" fill-opacity=".1"></path>
                            </svg>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <h3 class="font-bold text-xl flex items-center">
                                <i class="las la-th-list mr-2 bg-white/20 p-2 rounded-full"></i>
                                <span>Categories</span>
                            </h3>
                            <button
                                class="navbar-close text-white bg-red-500 p-2 rounded-full flex items-center justify-center border border-red-500">
                                <i class="las la-times text-xl leading-none"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mobile-category-wrapper overflow-hidden border-b shadow-inner relative">
                        <div class="flex transition-transform duration-300 ease-in-out"
                            :style="activeMobileSubCategory ? 'transform: translateX(-100%)' : 'transform: translateX(0)'">
                            {{-- Main categories view --}}
                            <div class="px-2 py-1 min-w-full overflow-y-auto" style="max-height: calc(100vh - 100px);">
                                @foreach ($header_categories as $key => $header_category)
                                    <div class="mobile-category-item !rounded-full mb-3">
                                        @if ($header_category->subCategories && $header_category->subCategories->count() > 0)
                                            {{-- Category with subcategories - clickable --}}
                                            <div class="flex items-center justify-between p-0.5 text-gray-700 hover:bg-primary/5 hover:text-primary rounded-full transition duration-300 cursor-pointer"
                                                @click="activeMobileSubCategory = {{ $header_category->id }}"  style="color: black !important">
                                                <div class="flex items-center">
                                                    <div class="category-icon-mobile mr-3">
                                                        <img src="{{ $header_category->picture_url }}"
                                                            alt="{{ $header_category->name }}"
                                                            class="w-8 h-8 rounded-full">
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">{{ $header_category->name }}</span>
                                                        @if ($key % 5 == 1)
                                                            <span
                                                                class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-md">HOT</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <i
                                                    class="las la-angle-right text-primary/70 transition-transform duration-300"></i>
                                            </div>
                                        @else
                                            {{-- Category without subcategories - directly linkable --}}
                                            <a class="flex items-center p-0.5 text-gray-700 hover:bg-primary/5 hover:text-primary rounded-full transition duration-300"
                                                href="{{ route('shop', ['categories' => [$header_category->id]]) }}" style="color: black !important">
                                                <div class="flex items-center">
                                                    <div class="category-icon-mobile mr-3">
                                                        <img src="{{ $header_category->picture_url }}"
                                                            alt="{{ $header_category->name }}"
                                                            class="w-8 h-8 rounded-full">
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">{{ $header_category->name }}</span>
                                                        @if ($key % 5 == 1)
                                                            <span
                                                                class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-md">HOT</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Subcategories view --}}
                            @foreach ($header_categories as $key => $header_category)
                                @if ($header_category->subCategories && $header_category->subCategories->count() > 0)
                                    <div class="px-2 py-1 min-w-full overflow-y-auto"
                                        style="max-height: calc(100vh - 100px);"
                                        x-show="activeMobileSubCategory === {{ $header_category->id }}">
                                        {{-- Back button --}}
                                        <div class="flex items-center mb-3 pb-2 border-b">
                                            <button @click="activeMobileSubCategory = null"
                                                class="flex items-center text-gray-600 hover:text-primary transition duration-300">
                                                <i class="las la-arrow-left text-xl mr-2"></i>
                                                <span class="font-medium">Back</span>
                                            </button>
                                        </div>
                                        {{-- Subcategories list --}}
                                        <div class="">
                                            @foreach ($header_category->subCategories as $subcategory)
                                                <a href="{{ route('shop', ['sub_categories' => [$subcategory->id]]) }}"
                                                    class="flex items-center py-2 px-2 text-gray-700 hover:bg-primary/5 hover:text-primary rounded-md transition duration-300 mb-2"  style="color: black !important">
                                                    <div class="w-2 h-2 bg-primary/30 rounded-full mr-3"></div>
                                                    <span class="font-medium text-sm">{{ $subcategory->name }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="flex lg:hidden justify-end items-center gap-5">
            <div>
                <a class="" href="{{ route('index') }}">
                    <img class="h-10" src="{{ asset($settings->website_logo ?? 'assets/frontend/images/logo.svg') }}"
                        alt="Logo">
                </a>
            </div>
            <div class="w-[150px]" x-data="getResults()" x-effect="await search(keyword)"
                @click.outside="keyword = ''">
                <div class="flex items-center w-full max-w-xl mx-auto justify-end">
                    <input x-model="keyword" type="text" name="search" id="search"
                        class="placeholder-marquee w-full max-w-xl mx-auto border border-text-primary focus:border-text-primary focus:ring-0 focus:bottom-0 pl-3 py-2 rounded-s rounded-e-none focus:outline-none flex text-sm leading-5"
                        placeholder="I'm Looking" style="height:36px;">
                    <button type="button"
                        class="flex items-center justify-center border-l border-secondary bg-text-primary text-primary rounded-e rounded-s-none px-3 py-1.5 h-[36px] w-[36px]">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <div class="fixed left-0 top-[60px] bg-white w-screen rounded border border-primary z-50"
                        x-transition x-show="keyword.length > 0" x-cloak>
                        <div class="p-4 w-full">
                            <template x-for="query in results">
                                <a :href="query.url" class="block w-full !text-primary py-2 hover:bg-gray-50 px-2">
                                    <span x-text="query.value"></span>
                                </a>
                            </template>
                            <div x-show="results.length == 0"
                                class="text-center px-[18px] py-3 gap-3 text-base font-medium text-gray-400 transition duration-200 ease-linear">
                                <span x-show="loader" class="animate-spin flex justify-center">
                                    <i class="las la-spinner"></i>
                                </span>
                                <span x-show="!loader">
                                    {{ __('No data matches your query') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('track.order') }}"
                    class="text-center text-text-primary hover:text-secondary transition relative">
                    <i class="!text-3xl text-text-primary hover:text-secondary las la-truck"></i>
                </a>
            </div>
        </div>
        <a class="hidden lg:block" href="{{ route('index') }}">
            <img class="h-10" src="{{ asset($settings->website_logo ?? 'assets/frontend/images/logo.svg') }}"
                alt="Logo">
        </a>
        <div class="hidden lg:block w-full px-8" x-data="getResults()" x-effect="await search(keyword)"
            @click.outside="keyword = ''">
            <div class="relative w-full max-w-xl mx-auto flex items-center">
                <span class="absolute left-4 top-1 text-lg text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input x-model="keyword" type="text" name="search" id="search"
                    class="placeholder-marquee bg-text-primary w-full max-w-xl mx-auto border border-primary hover:border-primary focus:bottom-0 pl-12 py-1 pr-3 rounded-md focus:outline-none hidden md:flex focus:ring-0"
                    placeholder="Search Product">
                <div class="absolute top-[52px] bg-white w-full rounded border border-primary z-50" x-transition
                    x-show="keyword.length > 0" x-cloak>
                    <div class="p-4 w-full">
                        <template x-for="query in results">
                            <a :href="query.url" class="block w-full !text-primary py-2 hover:bg-gray-50 px-2">
                                <span x-text="query.value"></span>
                            </a>
                        </template>
                        <div x-show="results.length == 0"
                            class="text-center px-[18px] py-3 gap-3 text-base font-medium text-gray-400 transition duration-200 ease-linear">
                            <span x-show="loader" class="animate-spin flex justify-center">
                                <i class="las la-spinner"></i>
                            </span>
                            <span x-show="!loader">
                                {{ __('No data matches your query') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block">
            <div class="flex items-center space-x-4 ml-4">
                <a href="{{ route('track.order') }}"
                    class="text-center text-text-primary hover:text-secondary transition relative">
                    <i class="!text-3xl las la-truck"></i>
                    <div class="text-xs leading-3">
                        Track
                    </div>
                </a>
                <a href="{{ route('shop') }}" class="text-center text-text-primary hover:text-secondary transition relative">
                    <i class="!text-3xl las la-cart-plus"></i>
                    <div class="text-xs leading-3">
                        Shop
                    </div>
                </a>
                @livewire('cart-icon')
                <a href="{{ route('user.dashboard') }}"
                    class="text-center text-text-primary hover:text-secondary transition relative">
                    <i class="!text-3xl las la-user-circle"></i>
                    <div class="text-xs leading-3">
                        Account
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- ./header -->

@push('scripts')
    <script>
        function getResults() {
            return {
                keyword: '',
                results: [],
                loader: false,
                async search(keyword) {

                    this.results = [];
                    this.loader = true;

                    // wait for 1 second before making the request
                    await new Promise(resolve => setTimeout(resolve, 1000));

                    let response = await axios.get('/search-autocomplete', {
                        params: {
                            keywords: this.keyword,
                        }
                    });

                    this.loader = false;
                    this.results = response.data;
                },
            }
        }
    </script>

    <script>
        // Burger menus
        document.addEventListener('DOMContentLoaded', function() {
            // open
            const burger = document.querySelectorAll('.navbar-burger');
            const menu = document.querySelectorAll('.navbar-menu');

            if (burger.length && menu.length) {
                for (var i = 0; i < burger.length; i++) {
                    burger[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }

            // close
            const close = document.querySelectorAll('.navbar-close');
            const backdrop = document.querySelectorAll('.navbar-backdrop');

            if (close.length) {
                for (var i = 0; i < close.length; i++) {
                    close[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }

            if (backdrop.length) {
                for (var i = 0; i < backdrop.length; i++) {
                    backdrop[i].addEventListener('click', function() {
                        for (var j = 0; j < menu.length; j++) {
                            menu[j].classList.toggle('hidden');
                        }
                    });
                }
            }
        });
    </script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll("#search"); // Select all inputs with the same ID 'search'
            let headerCategoriesTitle = {!! json_encode($header_categories->pluck('name')) !!};

            function getRandomCategory() {
                if (!headerCategoriesTitle.length) {
                    return "products"; // Default fallback in lowercase
                }
                let randomCategory = headerCategoriesTitle[Math.floor(Math.random() * headerCategoriesTitle
                    .length)];
                return randomCategory.toLowerCase(); // Convert category name to lowercase
            }

            function startTypingAnimation(input) {
                let categoryName = getRandomCategory();
                let text = `Search by ${categoryName}`;
                let index = 0;
                let isDeleting = false;

                function animatePlaceholder() {
                    if (!input) return; // Ensure input exists

                    if (!isDeleting) {
                        input.setAttribute("placeholder", text.substring(0, index));
                        index++;
                        if (index > text.length) {
                            isDeleting = true;
                            setTimeout(animatePlaceholder, 1000); // Pause before deleting
                            return;
                        }
                    } else {
                        input.setAttribute("placeholder", text.substring(0, index));
                        index--;
                        if (index === 0) {
                            isDeleting = false;
                            categoryName = getRandomCategory(); // Get a new category name
                            text = `Search by ${categoryName}`;
                            setTimeout(animatePlaceholder, 1000); // Pause before typing again
                            return;
                        }
                    }
                    setTimeout(animatePlaceholder, 100); // Typing speed
                }

                animatePlaceholder();
            }

            // Apply animation to all elements with the 'search' id (if used multiple times)
            inputs.forEach(input => {
                startTypingAnimation(input);
            });
        });
    </script> --}}
@endpush

<style>
    .mobile-category-sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%);
    }

    .sidebar-header {
        background-image: linear-gradient(135deg, var(--primary), var(--secondary), var(--primary));
        background-size: 200% 200%;
        animation: gradientBG 15s ease infinite;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .mobile-category-wrapper {
        max-height: calc(100vh - 100px);
    }

    .mobile-category-item {
        transition: all 0.3s ease;
        position: relative;
    }

    .mobile-category-item:hover {
        transform: translateX(5px);
    }

    /* Smooth slide animation */
    .mobile-category-wrapper>div>div {
        scroll-behavior: smooth;
    }

    .category-icon-mobile {
        background: rgba(var(--primary-rgb), 0.08);
        color: var(--primary);
        height: 36px;
        width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .mobile-category-item:hover .category-icon-mobile {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
        border-radius: 9999px;
    }
</style>
