<section
    x-data="heroSlider()"
    x-init="start()"
    class="relative h-[65vh] w-full overflow-hidden"
>
    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div
            x-show="active === index"
            x-transition.opacity.duration.700ms
            class="absolute inset-0"
        >
            <!-- Background image -->
            <div
                class="absolute inset-0 bg-cover bg-center"
                :style="`background-image:url(${slide.image})`"
            ></div>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60"></div>

            <!-- Content -->
            <div class="relative z-10 flex h-full items-center justify-center text-center px-4">
                <div class="max-w-3xl text-white">
                    <p class="mb-3 text-sm tracking-[0.3em] text-amber-400 uppercase">
                        <span x-text="slide.tag"></span>
                    </p>

                    <h1 class="mb-6 text-3xl md:text-5xl font-light leading-tight">
                        <span x-text="slide.title"></span>
                    </h1>

                    <p class="mb-8 text-sm md:text-base text-gray-200">
                        <span x-text="slide.subtitle"></span>
                    </p>

                    <a
                        href="#"
                        class="inline-block bg-amber-500 px-8 py-3 text-sm font-medium text-black transition hover:bg-amber-400"
                    >
                        Make An Appointment
                    </a>
                </div>
            </div>
        </div>
    </template>

    <!-- Left Arrow -->
    <button
        @click="prev"
        class="absolute left-4 top-1/2 z-20 -translate-y-1/2 bg-black/40 p-3 text-white hover:bg-black/60"
    >
        &#10094;
    </button>

    <!-- Right Arrow -->
    <button
        @click="next"
        class="absolute right-4 top-1/2 z-20 -translate-y-1/2 bg-black/40 p-3 text-white hover:bg-black/60"
    >
        &#10095;
    </button>

    <!-- Indicators -->
    <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button
                @click="active = index"
                class="flex h-7 w-7 items-center justify-center text-xs font-medium"
                :class="active === index ? 'bg-white text-black' : 'bg-black/50 text-white'"
                x-text="index + 1"
            ></button>
        </template>
    </div>
</section>
