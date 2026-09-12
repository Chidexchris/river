@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row gy-5 justify-content-center align-items-center">
        <div class="col-lg-6 col-md-10">
            <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                <div class="card-header">
                    <h3 class="title">@lang('Checkout.com')</h3>
                </div>
                <div class="card-body">
                    <div class="card-wrapper mb-4"></div>
                    <form action="{{ $data->url }}" method="{{ $data->method }}" class="row g-lg-4 g-3" id="payment-form">
                        @csrf
                        <input type="hidden" name="track" value="{{ $data->track }}">
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Name')</label>
                            <div class="input--group">
                                <input type="text" class="form--control" name="name" value="{{ old('name') }}" required autocomplete="off">
                                <span class="input-group-text"><i class="ti ti-user-scan"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Card Number')</label>
                            <div class="input--group">
                                <input type="text" class="form--control" name="cardNumber" value="{{ old('cardNumber') }}" required autocomplete="off">
                                <span class="input-group-text"><i class="ti ti-credit-card"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Expire Date')</label>
                            <div class="input--group">
                                <input type="text" class="form--control" name="cardExpiry" value="{{ old('cardExpiry') }}" required autocomplete="off">
                                <span class="input-group-text"><i class="ti ti-calendar-week"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('CVV Code')</label>
                            <div class="input--group">
                                <input type="text" class="form--control" name="cardCVC" value="{{ old('cardCVC') }}" required autocomplete="off">
                                <span class="input-group-text"><i class="ti ti-password-mobile-phone"></i></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/card.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });
        })(jQuery);
    </script>
@endpush
