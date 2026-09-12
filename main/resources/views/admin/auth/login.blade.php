@extends('admin.layouts.app')

@section('content')
    <section class="account bg-img" data-background-image="{{ asset('assets/admin/images/account-bg.png') }}">
        <div class="account__form">
            <div class="account__form__container">
                <div class="account__top d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}" target="_blank">
                            <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="account__form__content">
                    <div class="account__form__thumb">
                        <img src="{{ asset('assets/admin/images/wave.gif') }}" alt="Hi">
                    </div>
                    <h3 class="account__form__title">{{ __($setting->site_name) }}</h3>
                    <p>@lang('Please login to your account')</p>
                </div>
                <form action="{{ route('admin.login') }}" method="POST" class="verify-gcaptcha">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="form--label">@lang('Username')</label>
                            <input type="text" class="form--control" name="username" placeholder="@lang('Enter Your Username')" required autofocus>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="your-password" class="form--label">@lang('Password')</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form--control" name="password" id="your-password" placeholder="@lang('Enter Your Password')" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                            </div>
                        </div>

                        <x-captcha />

                        <div class="col-sm-12 form-group">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="form--check">
                                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                </div>
                                <a href="{{ route('admin.password.request.form') }}" class="forgot-password text--base">
                                    @lang('Forgot Password?')
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Log In')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
