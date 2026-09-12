@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <form action="{{ route('staff.accounts.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="countryCode" name="country_code">
                <input type="hidden" id="mobileCode" name="mobile_code">
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Image')</label>
                            <div class="upload__img">
                                <label for="image" class="upload__img__btn">
                                    <i class="ti ti-camera"></i>
                                </label>
                                <input type="file" id="image" class="image-upload" name="image" accept=".jpeg, .jpg, .png" required>
                                <label for="image" class="upload__img-preview image-preview">
                                    <i class="ti ti-photo-up"></i>
                                </label>
                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                    <i class="ti ti-circle-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-6">
                            <div class="row g-lg-4 g-3">
                                <div class="col-lg-6">
                                    <label for="first_name" class="form--label required">@lang('First Name')</label>
                                    <input type="text" id="first_name" class="form--control" name="firstname" value="{{ old('firstname') }}" required>
                                </div>
                                <div class="col-lg-6">
                                    <label for="last_name" class="form--label required">@lang('Last Name')</label>
                                    <input type="text" id="last_name" class="form--control" name="lastname" value="{{ old('lastname') }}" required>
                                </div>
                                <div class="col-lg-6">
                                    <label for="username" class="form--label required">@lang('Username')</label>
                                    <input type="text" id="username" class="form--control check-user" name="username" value="{{ old('username') }}" required>
                                    <small></small>
                                </div>
                                <div class="col-lg-6">
                                    <label for="email" class="form--label required">@lang('Email Address')</label>
                                    <input type="email" id="email" class="form--control check-user" name="email" value="{{ old('email') }}" required>
                                    <small></small>
                                </div>
                                <div class="col-lg-6">
                                    <label for="country" class="form--label required">@lang('Country')</label>
                                    <select id="country" class="form--control form-select select-2" name="country_name" required>
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $country->country }}" data-country_code="{{ $key }}" data-dial_code="{{ $country->dial_code }}" @selected(old('country_name') == $country->country)>
                                                {{ $country->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="mobile" class="form--label required">@lang('Contact Number')</label>
                                    <div class="input--group">
                                        <span class="input-group-text" id="dialCode"></span>
                                        <input type="text" id="mobile" class="form--control check-user" name="mobile" value="{{ old('mobile') }}" required>
                                    </div>
                                    <small></small>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form--label required">@lang('Address')</label>
                                    <input type="text" id="address" class="form--control" name="address" value="{{ old('address') }}" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="city" class="form--label required">@lang('City')</label>
                                    <input type="text" id="city" class="form--control" name="city" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="state" class="form--label">@lang('State')</label>
                                    <input type="text" id="state" class="form--control" name="state" value="{{ old('state') }}">
                                </div>
                                <div class="col-lg-4">
                                    <label for="zip_code" class="form--label required">@lang('ZIP Code')</label>
                                    <input type="text" id="zip_code" class="form--control" name="zip_code" value="{{ old('zip_code') }}" required>
                                </div>

                                @if($isReferralSystemEnabled)
                                    <div class="col-12">
                                        <label for="referrer" class="form--label">
                                            @lang('Referrer') <span title="@lang('Account number of the person who has referred')"><i class="ti ti-info-circle fz-2"></i></span>
                                        </label>
                                        <input type="text" id="referrer" class="form--control" name="ref_by" value="{{ old('ref_by') }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('staff.accounts.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            function checkUniqueness(fieldName, fieldValue, feedbackElement) {
                $.get("{{ route('staff.accounts.check.unique') }}", { field: fieldName, value: fieldValue }, function (response) {
                    if (response.exists) {
                        feedbackElement.text(`This ${fieldName} is already taken.`).removeClass('text--success').addClass('text--danger')
                    } else {
                        feedbackElement.text(`This ${fieldName} is available.`).removeClass('text--danger').addClass('text--success')
                    }
                })
            }

            $(function () {
                let country = $('#country')
                let initSelectedOpt = country.find(':selected')

                $('#countryCode').val(`${initSelectedOpt.data('country_code')}`)
                $('#mobileCode').val(`${initSelectedOpt.data('dial_code')}`)
                $('#dialCode').text(`+${initSelectedOpt.data('dial_code')}`)

                country.on('change', function () {
                    let selectedOpt = $(this).find(':selected')

                    $('#countryCode').val(`${selectedOpt.data('country_code')}`)
                    $('#mobileCode').val(`${selectedOpt.data('dial_code')}`)
                    $('#dialCode').text(`+${selectedOpt.data('dial_code')}`)
                })

                $('.check-user').on('focusout', function () {
                    let inputField = $(this)
                    let fieldName = inputField.attr('name')
                    let fieldValue = inputField.val().trim()
                    let feedbackElement

                    if (fieldName === 'mobile') {
                        let dialCode = $('#mobileCode').val().trim()
                        fieldValue = `${dialCode}${fieldValue}`
                        feedbackElement = inputField.closest('.input--group').next('small')

                        if (dialCode !== '' && fieldValue.replace(dialCode, '').trim() !== '') {
                            checkUniqueness(fieldName, fieldValue, feedbackElement)
                        } else {
                            feedbackElement.text('Please enter a valid mobile number.').removeClass('text--success').addClass('text--danger')
                        }
                    } else {
                        feedbackElement = inputField.next('small')

                        if (fieldValue !== '') {
                            checkUniqueness(fieldName, fieldValue, feedbackElement)
                        } else {
                            feedbackElement.text('')
                        }
                    }
                })
            })
        })(jQuery)
    </script>
@endpush
