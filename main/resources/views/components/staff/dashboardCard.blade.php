<div class="col-xl-3 col-sm-6">
    <div {{ $attributes->merge(['class' => 'dashboard-widget-1']) }}>
        <div class="dashboard-widget-1__icon">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="dashboard-widget-1__content">
            <h3 class="dashboard-widget-1__number">{{ $value }}</h3>
            <p class="dashboard-widget-1__txt">{{ $text }}</p>
        </div>
    </div>
</div>
