<button type="button" wire:click="toggle" aria-label="Toggle favorite">
    @if ($isFavorite)
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-red-500">
            <path fill-rule="evenodd" d="M11.645 20.91l-.007-.003-.022-.01a15.247 15.247 0 01-.383-.18 25.18 25.18 0 01-4.244-2.632C4.688 16.045 2.25 13.36 2.25 9.818 2.25 7.123 4.273 5.25 6.75 5.25c1.592 0 3.057.788 3.999 2.05a4.972 4.972 0 013.999-2.05c2.477 0 4.5 1.873 4.5 4.568 0 3.542-2.438 6.227-4.739 8.268a25.175 25.175 0 01-4.244 2.632 15.247 15.247 0 01-.383.18l-.022.01-.007.003a.75.75 0 01-.592 0z" clip-rule="evenodd" />
        </svg>
    @else
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
        </svg>
    @endif
</button>


