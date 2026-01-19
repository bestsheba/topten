<div class="lg:hidden w-full relative bg-black" x-data="getResults()" x-effect="await search(keyword)"
    @click.outside="keyword = ''">
    <div class="w-full px-2 flex items-center">
        <span class="absolute left-7 top-2 text-lg text-gray-400">
            <i class="fa-solid fa-magnifying-glass"></i>
        </span>
        <input x-model="keyword" type="text" name="search" id="search"
            class="!w-full border border-primary py-1 mb-1.5 my-1 hover:border-primary focus:bottom-0 pl-12 pr-3 rounded focus:outline-none md:flex focus:ring-0"
            placeholder="Search Product">
    </div>
    <div class="absolute top-[45px] bg-white border-b-2 border-primary/30 w-full z-50" x-transition
        x-show="keyword.length > 0" x-cloak>
        <div class="p-4 w-full">
            <template x-for="query in results">
                <a :href="query.url" class="block text-left w-full hover:bg-gray-50 px-2">
                    <span x-text="query.value"></span>
                </a>
            </template>
            <div x-show="results.length == 0"
                class="text-center px-[18px] gap-3 text-base font-medium text-gray-400 transition duration-200 ease-linear">
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
