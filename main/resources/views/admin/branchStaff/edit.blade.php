@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.branch.staffs.update', $staff) }}" method="post">
            @csrf
            <div class="custom--card">
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Name')</label>
                            <input type="text" class="form--control" name="name" value="{{ old('name', $staff->name) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Username')</label>
                            <input type="text" class="form--control" name="username" value="{{ old('username', $staff->username) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Email Address')</label>
                            <input type="email" class="form--control" name="email_address" value="{{ old('email_address', $staff->email_address) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Contact Number')</label>
                            <input type="text" class="form--control" name="contact_number" value="{{ old('contact_number', $staff->contact_number) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Address')</label>
                            <input type="text" class="form--control" name="address" value="{{ old('address', $staff->address) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Designation')</label>
                            <select class="form--control form-select" name="designation" id="designation" required>
                                <option disabled>@lang('Select One')</option>
                                <option value="{{ ManageStatus::BRANCH_MANAGER }}" @selected(old('designation', $staff->designation) == ManageStatus::BRANCH_MANAGER)>
                                    @lang('Manager')
                                </option>
                                <option value="{{ ManageStatus::BRANCH_ACCOUNT_OFFICER }}" @selected(old('designation', $staff->designation) == ManageStatus::BRANCH_ACCOUNT_OFFICER)>
                                    @lang('Account Officer')
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label required">@lang('Branch')</label>
                            <select class="form--control form-select select-2" name="branch_ids[]" @if(old('designation', $staff->designation) == ManageStatus::BRANCH_MANAGER) multiple @endif id="branchIds" required>
                                <option disabled>@lang('Select One')</option>

                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected(in_array($branch->id, old('branch_ids', $staff->branches->pluck('id')->toArray())))>
                                        {{ __($branch->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form--label">@lang('Password')</label>
                            <div class="input--group">
                                <input type="password" class="form--control" id="password" name="password" value="{{ old('password') }}">
                                <button type="button" class="btn btn--base" id="generatePassword">
                                    @lang('Generate')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @can('update branch staff')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Update')</button>
                        </div>
                    </div>
                @endcan
            </div>
        </form>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.branch.staffs.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            // Function to generate a random password
            function generatePassword(length = 12) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:,.<>?'
                let password = ''

                for (let i = 0; i < length; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length))
                }

                return password
            }

            $(function () {
                let branchManager = `{{ ManageStatus::BRANCH_MANAGER }}`

                $('#designation').on('change', function () {
                    let designation = parseInt($(this).find('option:selected').val())
                    let branchIds = $('#branchIds')

                    if (designation == branchManager) {
                        branchIds.attr('multiple', 'multiple').val(null)
                    } else {
                        let firstOptVal = $('#branchIds option:first').val()
                        branchIds.removeAttr('multiple').val(firstOptVal)
                    }

                    // first, destroy select2
                    branchIds.select2('destroy')

                    // then, reinitialize select2
                    branchIds.select2({
                        containerCssClass: ":all:",
                        dropdownAutoWidth: true,
                        tags: true,
                    })
                })

                // Bind generate password button in both modals dynamically
                $('#generatePassword').on('click', function () {
                    const newPassword = generatePassword()

                    $('#password').val(newPassword)
                })
            })
        })(jQuery)
    </script>
@endpush
