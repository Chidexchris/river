@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row gy-5 justify-content-center align-items-center">
        <div class="col-lg-6 col-md-10">
            <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                <div class="card-header">
                    <h3 class="title">@lang('Flutterwave')</h3>
                </div>
                <div class="card-body">
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
                        <button type="button" class="btn btn--base w-100 mt-3" onClick="payWithRave()">
                            @lang('Pay Now')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
@endpush

@push('page-script')
    <script>
        "use strict"

        function payWithRave() {
            getpaidSetup({
                PBFPubKey: "{{ $data->API_publicKey }}",
                customer_email: "{{ $data->customer_email }}",
                amount: "{{ $data->amount }}",
                customer_phone: "{{ $data->customer_phone }}",
                currency: "{{ $data->currency }}",
                txref: "{{ $data->txref }}",
                onclose: function() {},
                callback: function(response) {
                    let txRef = response.tx.txRef;
                    let status = response.tx.status;
                    let chargeResponse = response.tx.chargeResponseCode;

                    window.location = "{{ url('ipn/flutterwave') }}/" + txRef + "/" + status;
                }
            })
        }
    </script>
@endpush
