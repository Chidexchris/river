@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="custom--card">
        <div class="card-header">
            <h3 class="title">@lang('Two-Factor Authentication')</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="two-fa-setting">
                        <p>@lang('Two factor authentication provides extra protection for your account by requiring a special code.')</p>

                        @if (!$user->ts)
                            <p><strong>@lang('Note'):</strong> @lang('You are enabling two-factor authentication to add an extra layer of security when you log in.')</p>
                        @else
                            <p><strong>@lang('Note'):</strong> @lang('You are disabling two-factor authentication, and it will have no effect when you log in.')</p>
                        @endif

                        <p>@lang('Have a smart phone? Use Google Authenticator.')</p>
                        <div class="download-app">
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US" target="_blank">
                                <img src="{{ asset($activeThemeTrue . 'images/google-play.png') }}" alt="Play Store">
                            </a>
                            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
                                <img src="{{ asset($activeThemeTrue . 'images/app-store.png') }}" alt="App Store">
                            </a>
                        </div>
                        <p class="fw-semibold text--secondary">
                            <em><small>@lang('Google Authenticator is a software-based authenticator developed by Google, which implements two-step verification services using Time-based One-time Passwords (TOTP). It is designed to authenticate users of mobile applications by generating a six to eight-digit one-time password. Google Authenticator works seamlessly with multiple accounts on a single device, enabling users to implement two-factor authentication (2FA) for various online accounts, thereby enhancing security.')</small></em>
                        </p>

                        @if (!$user->ts)
                            <p>
                                @lang('To enable two-factor authentication, scan the QR code located on the right side using Google Authenticator. Once you have successfully scanned the QR code, enter the generated token from Google Authenticator into the "Google Authenticator OTP" field. We ensure that you can generate tokens correctly before enabling two-factor authentication for added security.')
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    @if(!$user->ts)
                        <div class="alert alert--base">
                            <span class="alert__content w-100 ps-0">
                                <small>
                                    <strong>@lang('Use the QR code or setup key on your Google Authenticator app to add your account.')</strong>
                                </small>
                            </span>
                        </div>
                        <div class="qr-code-img my-4">
                            <img src="{{ $qrCodeUrl }}" alt="QR Code">
                        </div>
                        <div class="account-setup-key">
                            <div class="form-group mb-4">
                                <label class="form--label">@lang('Setup Key')</label>
                                <div class="input--group referral-link">
                                    <input type="text" class="form--control" id="accountSetupKey" name="key" value="{{ $secret }}" readonly>
                                    <button class="btn btn--base account-setup-key__copy">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <form action="{{ route('user.twofactor.enable') }}" method="POST" class="verification-code-form">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <label class="form--label required">@lang('Google Authenticator OTP')</label>
                                <div class="mb-3">
                                    @include('partials.verificationCode')
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert--base">
                            <span class="alert__content w-100 ps-0">
                                <small>
                                    <strong>@lang('To disable two-factor authentication, enter the token from Google Authenticator into the "Google Authenticator OTP" field.')</strong>
                                </small>
                            </span>
                        </div>
                        <div class="account-setup-key mt-3">
                            <form action="{{ route('user.twofactor.disable') }}" method="POST" class="verification-code-form">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <label class="form--label required">@lang('Google Authenticator OTP')</label>
                                <div class="mb-3">
                                    @include('partials.verificationCode')
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
