@props([
    'class' => '',
    'link' => '',
])
@if ($link)
    <a href="{{ $link }}" class="{{ $class }}">
        <div class="info-box">
            {{ $icon ?? '' }}
            <div class="info-box-content">
                <span class="info-box-text">
                    {{ $title ?? '' }}
                </span>
                @isset($number)
                    <span class="info-box-number">
                        {{ $number }}
                    </span>
                @endisset
            </div>
        </div>
    </a>
@else
    <div class="{{ $class }}">
        <div class="info-box">
            {{ $icon ?? '' }}
            <div class="info-box-content">
                <span class="info-box-text">
                    {{ $title ?? '' }}
                </span>
                @isset($number)
                    <span class="info-box-number">
                        {{ $number }}
                    </span>
                @endisset
            </div>
        </div>
    </div>
@endif
