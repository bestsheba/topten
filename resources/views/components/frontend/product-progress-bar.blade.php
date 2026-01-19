@props(['sold'])
@php

    if (env('RANDOM_SOLD')) {
        $steps = range(rand(30, 170), 850, 100); // 50, 150, 250, 350, ..., 850
        $sold = $steps[array_rand($steps)];
    }

    $maxSold = ceil($sold / 100) * 100; // Next 100 step
    $remaining = $maxSold - $sold; // How much missing

    $percentage = ($remaining / 100) * 100;
    $percentage = min(100, max(1, $percentage)); // clamp 1%-100%
@endphp

<div class="px-2 mb-2">
    <div class="border w-full rounded-full">
        <div class="w-[{{ $sold <= 100 ? $sold : number_format($percentage, 0) }}%]">
            <div class="bg-gradient-to-r from-red-500 to-red-200 rounded-full h-6 overflow-hidden">
                <div class="flex items-center h-full pl-4">
                    <i class="fas fa-fire mr-2 text-white"></i>
                    <span class="font-medium text-xs text-white whitespace-nowrap">{{ $sold }} Sold</span>
                </div>
            </div>
        </div>
    </div>
</div>
