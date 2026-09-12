@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row gy-5 justify-content-center align-items-center">
        <div class="col-lg-6 col-md-10">
            <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                <div class="card-header">
                    <h3 class="title">{{ __($cardTitle) . ' ' . __($gateway?->name) }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data" class="row gy-3">
                        @csrf
                        <div class="col-12 text-center">
                            <p class="fw-bold text--muted">
                                {{ __($previewText) }} <span class="text--base">{{ showAmount($deposit['amount']) . ' ' . __($setting->site_cur) }}</span>, @lang('Please pay') <span class="text--base">{{ showAmount($deposit['final_amount']) . ' ' . $deposit['method_currency'] }}</span> @lang('for the successful payment.')
                            </p>
                            <h5 class="mt-3 mb-0">@lang('Please follow the instruction below')</h5>
                        </div>
                        <div class="col-12">
                            @php echo $gateway?->guideline @endphp
                        </div>

                        <x-phinix-form identifier="id" identifierValue="{{ $gateway?->form_id }}" />

                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
