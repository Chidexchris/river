@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row gy-5 justify-content-center align-items-center">
        <div class="col-lg-6 col-md-10">
            <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                <div class="card-header">
                    <h3 class="title">@lang('Razorpay')</h3>
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
                        <form action="{{ $data->url }}" method="{{ $data->method }}">
                            @csrf
                            <input type="hidden" custom="{{ $data->custom }}" name="trx">
                            <script src="{{ $data->checkout_js }}" @foreach ($data->val as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach></script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            "use strict"

            $('input[type="submit"]').addClass("btn btn--base w-100 mt-3")
        })(jQuery)
    </script>
@endpush
