@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <form action="" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form--label required">@lang('Email Delivery Method')</label>
                            <select class="form--control form-select" name="email_method" required>
                                <option value="php" @selected($setting->mail_config?->name == 'php')>
                                    @lang('PHP Mail')
                                </option>
                                <option value="smtp" @selected($setting->mail_config?->name == 'smtp')>
                                    @lang('SMTP')
                                </option>
                                <option value="sendgrid" @selected($setting->mail_config?->name == 'sendgrid')>
                                    @lang('SendGrid API')
                                </option>
                                <option value="mailjet" @selected($setting->mail_config?->name == 'mailjet')>
                                    @lang('Mailjet API')
                                </option>
                            </select>
                        </div>
                        <div class="col-12 configForm" id="smtp">
                            <div class="custom--card">
                                <div class="card-header">
                                    <h3 class="title">@lang('SMTP Config')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Host')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="host" value="{{ $setting->mail_config?->host ?? '' }}" placeholder="@lang('smtp.demoemail.com')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Port')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="port" value="{{ $setting->mail_config?->port ?? '' }}" placeholder="@lang('Available Port')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Encryption')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <select class="form--control form-select" name="enc">
                                                        <option value="ssl" @selected(($setting->mail_config?->enc ?? '') == 'ssl')>
                                                            @lang('SSL')
                                                        </option>
                                                        <option value="tls" @selected(($setting->mail_config?->enc ?? '') == 'tls')>
                                                            @lang('TLS')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Username')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="username" value="{{ $setting->mail_config?->username ?? '' }}" placeholder="@lang('Username')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Password')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="password" value="{{ $setting->mail_config?->password ?? '' }}" placeholder="@lang('Password')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 configForm" id="sendgrid">
                            <div class="custom--card">
                                <div class="card-header">
                                    <h3 class="title">@lang('SendGrid API Config')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('App Key')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="appkey" value="{{ $setting->mail_config?->appkey ?? '' }}" placeholder="@lang('SendGrid App Key')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 configForm" id="mailjet">
                            <div class="custom--card">
                                <div class="card-header">
                                    <h3 class="title">@lang('Mailjet API Config')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('API Public Key')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="public_key" value="{{ $setting->mail_config?->public_key ?? '' }}" placeholder="@lang('Mailjet API Public Key')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('API Secret Key')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="secret_key" value="{{ $setting->mail_config?->secret_key ?? '' }}" placeholder="@lang('Mailjet API Secret Key')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update email configuration')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="testEmailModalLabel">@lang('Send Test Mail')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.notification.email.test') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label class="form--label required">@lang('Send To')</label>
                            <input type="email" class="form--control form--control--sm" name="email" placeholder="@lang('Email Address')" required>
                        </div>
                        <div class="modal-footer gap-2">
                            <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Send')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@can('send test email')
    @push('breadcrumb')
        <button type="button" class="btn btn--sm btn--base" data-bs-target="#testEmailModal" data-bs-toggle="modal">
            <span class="ti ti-mail"></span> @lang('Test Mail')
        </button>
    @endpush
@endcan

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            let method = '{{ $setting->mail_config->name }}';

            emailMethod(method);

            $('select[name=email_method]').on('change', function () {
                let method = $(this).val();

                emailMethod(method);
            });

            function emailMethod (method) {
                $('.configForm').addClass('d-none');

                if(method !== 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }
        })(jQuery);
    </script>
@endpush
