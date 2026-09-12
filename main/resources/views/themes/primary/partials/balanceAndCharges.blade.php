<div class="col-12">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-sm-6 col-xsm-6">
            <div class="withdraw__card">
                <div class="withdraw__card__icon"><i class="ti ti-wallet"></i></div>
                <div class="withdraw__card__content">
                    <span class="withdraw__card__title">@lang('Current Balance')</span>
                    <span class="withdraw__card__number">{{ showAmount($user->balance) . ' ' . $setting->site_cur }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xsm-6">
            <div class="withdraw__card">
                <div class="withdraw__card__icon"><i class="ti ti-coins"></i></div>
                <div class="withdraw__card__content">
                    <span class="withdraw__card__title">@lang('Fixed Charge')</span>
                    <span class="withdraw__card__number"><span id="fixedCharge">0.00</span> {{ $setting->site_cur }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xsm-6">
            <div class="withdraw__card">
                <div class="withdraw__card__icon"><i class="ti ti-percentage"></i></div>
                <div class="withdraw__card__content">
                    <span class="withdraw__card__title">@lang('Percentage Charge')</span>
                    <span class="withdraw__card__number"><span id="percentageCharge">0</span>%</span>
                </div>
            </div>
        </div>
    </div>
</div>
