@php $dpsContent = getSiteData('dps.content', true) @endphp

<div class="col-xl-4 col-lg-5 col-md-6" @if(request()->routeIs('home')) data-aos="fade-up" data-aos-duration="1500" @endif>
    <div class="pricing__card">
        <div class="pricing__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/dps/' . $dpsContent->data_info->card_background, '500x610') }}"></div>
        <div class="pricing__card__top">
            <span class="pricing__card__icon">@php echo $dpsPlan->icon @endphp</span>
            <div class="pricing__card__txt">
                <span class="pricing__card__name">{{ __($dpsPlan->name) }}</span>
                <h3 class="pricing__card__price">{{ $setting->cur_sym . showAmount($dpsPlan->per_installment) }} <span>{{ '/' . $dpsPlan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $dpsPlan->installment_interval)) }}</span></h3>
            </div>
        </div>
        <div class="pricing__card__middle">
            <ul>
                <li>@lang('Total Installment') <span>{{ $dpsPlan->total_installment }}</span></li>
                <li>@lang('Deposit Amount') <span>{{ $setting->cur_sym . showAmount($dpsPlan->total_deposit_amount) }}</span></li>
                <li>@lang('Interest Rate') <span>{{ showAmount($dpsPlan->interest_rate) . '%' }}</span></li>
                <li>@lang('You Will Get') <span>{{ $setting->cur_sym . showAmount($dpsPlan->maturity_amount) }}</span></li>
            </ul>
        </div>
        <div class="pricing__card__bottom">
            <a href="{{ route('user.dps.plan.preview', ['plan' => $dpsPlan]) }}" class="btn btn--base">
                @lang('Choose Plan')
            </a>
        </div>
    </div>
</div>
