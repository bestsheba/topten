@props(['blog'])

<article class="flex flex-col dark:bg-gray-50 rounded-lg">
    <a rel="noopener noreferrer" href="#" aria-label="Te nulla oportere reprimique his dolorum">
        <img alt="{{ $blog->title }}" class="object-cover w-full h-52 dark:bg-gray-500" src="{{ $blog->thumbnail_url }}">
    </a>
    <div class="flex flex-col flex-1 p-6">
        <a rel="noopener noreferrer" href="" aria-label="{{ $blog->title }}">
        </a>
        {{-- <a rel="noopener noreferrer" href="#"
            class="text-xs tracking-wider uppercase hover:underline dark:text-violet-600">
            Convenire
        </a> --}}
        <h3 class="flex-1 py-2 text-lg font-semibold leading-snug">
            {{ $blog->title }}
        </h3>
        <div class="flex flex-wrap justify-between pt-3 space-x-2 text-xs dark:text-gray-600">
            <span>
                {{ $blog->publish_date }}
            </span>
        </div>
    </div>
</article>
