@props([
    'title' => '',
    'count' => '0',
    'color' => '#28a745',
])

@php
    $html_statuses_with_colors = [
        'info' => '#17a2b8',
        'primary' => '#007bff',
        'secondary' => '#6c757d',
        'success' => '#28a745',
        'danger' => '#dc3545',
        'dark' => '#343a40',
        'warning' => '#ffc107',
    ];
@endphp
<div class="col-md-3">
    <div class="card p-4 text-center">
        <div class="circular-stat" style="margin: 0 auto;">
            <svg viewBox="0 0 36 36" class="circular-chart" width="120" height="120">
                <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    fill="none" stroke="#eee" stroke-width="3" />
                <path class="circle" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    fill="none" stroke="{{ $html_statuses_with_colors[$color] }}" stroke-width="3" />
                <text x="18" y="20.35" class="count"
                    style="font-size: 10px; text-anchor: middle; fill: #333; font-weight: bold;">
                    {{ $count }}
                </text>
            </svg>
        </div>
        <div class="mt-3">
            <h5 style="color: {{ $html_statuses_with_colors[$color] }}">
                {{ $title }}
            </h5>
            <p class="text-muted small">
                Total <strong style="color: {{ $html_statuses_with_colors[$color] }}">{{ $title }}</strong> orders
            </p>
        </div>
    </div>
</div>
