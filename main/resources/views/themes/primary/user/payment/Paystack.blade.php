@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row gy-5 justify-content-center align-items-center">
        <div class="col-lg-6 col-md-10">
            <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                <div class="card-header">
                    <h3 class="title">@lang('Paystack')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('ipn.' . $deposit->gateway->alias) }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-borderless table-light no-shadow">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="fw-bold">@lang('You have to pay'):</span>
                                            </td>
                                            <td>
                                                {{ showAmount($deposit->final_amount) . ' ' . __($deposit->method_currency) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ __($label) . ':' }}</span>
                                            </td>
                                            <td>
                                                {{ showAmount($deposit->amount) . ' ' . __($setting->site_cur) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn--base w-100 mt-3" id="btn-confirm">
                                @lang('Pay Now')
                            </button>
                        </div>
                        <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}" data-email="{{ $data->email }}" data-amount="{{ round($data->amount) }}" data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}" data-custom-button="btn-confirm"></script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
