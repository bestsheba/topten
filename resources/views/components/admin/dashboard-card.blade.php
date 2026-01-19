@props([
    'title' => '',
    'count' => '0',
    'background' => 'bg-primary',
    'icon' => '',
    'url' => 'javascript:void(0)',
])

<div class="col-lg-3 col-12">
    <div class="card text-white {{ $background }}" style="border-radius: 4px">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center customPadding" style="padding: 0px !important">
                <div>
                    <div class="card-title text-white">
                        <div class="card-title text-white" style="float: unset; font-size:2rem">
                            {{ $title }}
                        </div>
                        <div class="text-white" style="width: 100%; font-weight:600; font-size:2rem">
                            {{ number_format($count, 0) }}
                        </div>
                    </div>
                    <div></div>
                    <a href="{{ $url }}" class="btn btn-sm btn-light text-dark mt-2" style="border-radius:2px">
                        View All
                    </a>
                </div>
                <div class="icon-container" style="z-index: 1">
                    {!! $icon !!}
                </div>
            </div>
        </div>
    </div>
</div>
