@php
    $countersContent  = getSiteData('counters.content', true);
    $countersElements = getSiteData('counters.element', false, null, true)
@endphp

<div class="counter-section py-120">
    <div class="container">
        <div class="row counter-section__row g-4 justify-content-center">
            @forelse($countersElements as $counter)
                <div class="col-md-3 col-sm-6 col-xsm-6">
                    <div class="counter-section__card" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        <span class="counter-section__icon"><img src="{{ getImage($activeThemeTrue . 'images/site/counters/' . $counter->data_info->icon, '200x200') }}" alt="Icon"></span>
                        <h3 class="counter-section__number odometer" data-count="{{ $counter->data_info->counter_number }}">0</h3>
                        <p class="counter-section__name">{{ __($counter->data_info->title) }}</p>
                    </div>
                </div>
            @empty
                @include($activeTheme . 'partials.basicNoData')
            @endforelse
        </div>
    </div>
</div>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/odometer.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/odometer.min.js') }}"></script>
@endpush
