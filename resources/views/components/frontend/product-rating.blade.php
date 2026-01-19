@props(['rating'])

<div class="flex items-center">
    <div class="flex gap-1 text-sm">
        <span><i class="fa-solid fa-star {{ $rating >= 1 ? 'text-yellow-400' : 'text-gray-400' }}"></i></span>
        <span><i class="fa-solid fa-star {{ $rating >= 2 ? 'text-yellow-400' : 'text-gray-400' }}"></i></span>
        <span><i class="fa-solid fa-star {{ $rating >= 3 ? 'text-yellow-400' : 'text-gray-400' }}"></i></span>
        <span><i class="fa-solid fa-star {{ $rating >= 4 ? 'text-yellow-400' : 'text-gray-400' }}"></i></span>
        <span><i class="fa-solid fa-star {{ $rating == 5 ? 'text-yellow-400' : 'text-gray-400' }}"></i></span>
    </div>
    <div class="text-xs text-gray-500 ml-3">
        ({{ $rating }})
    </div>
</div>
