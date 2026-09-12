@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4 justify-content-center">
        <div class="col-lg-6">
            <div class="custom--card">
                <div class="card-body">
                    <div class="alert alert--base">
                        <span class="alert__content w-100 ps-0">
                            @if($sendVia === 'email')
                                @lang('Please check your email for the six-digit OTP.')
                            @else
                                @lang('Please check your mobile for the six-digit OTP.')
                            @endif <strong>@lang('Your OTP will expire in the next')</strong>
                        </span>
                    </div>
                    <div class="otp-timer-container mt-4">
                        <div id="otpTimer" class="otp-timer" data-remaining_time="{{ $remainingTime }}"></div>
                    </div>
                    <div class="account-setup-key mt-4">
                        <form action="{{ route('user.otp.verify') }}" method="POST" class="verification-code-form">
                            @csrf
                            <div class="mb-4">
                                @include('partials.verificationCode')
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('Verify')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .otp-timer-container {
            max-width: 100%;
        }

        .otp-timer {
            position: relative;
            width: max-content;
            margin: auto;
        }

        .otp-timer canvas {
            transform: rotateY(180deg);
        }

        .otp-timer .timer-txt {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            aspect-ratio: 1 / 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .otp-timer .timer-value {
            display: block;
            font-size: 30px;
            line-height: 1;
            font-weight: 600;
        }

        .otp-timer .timer-label {
            display: block;
        }

        .timer-msg {
            text-align: center;
            padding-top: 5px;
        }

        .timer-msg span {
            display: block;
            font-weight: 500;
            color: hsl(var(--danger-d-200));
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset("{$activeThemeTrue}js/jquery.countdown360.min.js") }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let otpTimerSelector = $('#otpTimer')
                let remainingTime = otpTimerSelector.data('remaining_time')
                let baseColor = `{{ $setting->primary_color }}`

                let countdown = otpTimerSelector.countdown360({
                    radius: 93,
                    strokeStyle: `#${baseColor}`,
                    strokeWidth: 7,
                    fillStyle: "#ebe9ff",
                    fontColor: "#474747",
                    fontSize: 0.01,
                    autostart: false,
                    seconds: remainingTime,
                    label: ["Second", "Seconds"],
                    smooth: true,
                    onComplete: function () {
                        otpTimerSelector.after(`
                            <div class="timer-msg">
                                <span>Your OTP has expired!</span>
                                <form action="{{ route('user.otp.regenerate') }}" method="post">
                                    @csrf
                                    <button class="btn btn--sm btn--base py-1">Resend OTP</button>
                                </form>
                            </div>
                        `)
                    }
                })

                otpTimerSelector.append(`
                    <div class="timer-txt">
                        <span class="timer-value">${remainingTime}</span>
                        <span class="timer-label">Seconds</span>
                    </div>
                `)

                countdown.start()

                let updateTimer =  setInterval(() => {
                    let remainingSecs = countdown.getTimeRemaining()

                    $('.timer-value').text(remainingSecs)
                    $('.timer-label').text(remainingSecs > 1 ? 'Seconds' : 'Second')

                    if (remainingSecs <= 0) clearInterval(updateTimer)
                }, 1000)
            })
        })(jQuery)
    </script>
@endpush
