@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('You have requested to invest in a FDS plan')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.fds.plan.confirm') }}" method="post" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <table class="table table-borderless no-shadow mb-2">
                                <tbody>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Plan')</span></td>
                                        <td>{{ __($plan->name) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Interest Rate')</span></td>
                                        <td>{{ $plan->interest_rate . '%' }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Your Investment Amount')</span></td>
                                        <td>{{ $setting->cur_sym . showAmount($investAmount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ trans('Profitable Amount') . ' (' . trans('Every') . ' ' . $plan->interest_payout_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $plan->interest_payout_interval)) . ')' }}</span>
                                        </td>
                                        <td>{{ $setting->cur_sym . showAmount($profitableAmount) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-bold">@lang('Withdrawal Not Allowed Until')</span></td>
                                        <td class="text--base">{{ showDateTime($lockedPeriod, 'd M, Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('user.fds.plans') }}" class="btn btn--sm btn--secondary px-4">@lang('Cancel')</a>

                                @if($setting->email_based_otp || $setting->sms_based_otp)
                                    <button type="button" class="btn btn--sm btn--base px-4" data-bs-toggle="modal" data-bs-target="#authorizationModal">
                                        @lang('Confirm')
                                    </button>
                                @else
                                    <button type="submit" class="btn btn--sm btn--base px-4">@lang('Confirm')</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="authorizationModal" tabindex="-1" aria-labelledby="authorizationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="authorizationModalLabel">@lang('Verify Your Identity')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.fds.plan.confirm') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="authorizationMode" class="form--label required">@lang('Authorization Mode')</label>
                            <select id="authorizationMode" class="form--control form--control--sm wide" name="authorization_mode" required>
                                <option selected disabled>@lang('Select One')</option>

                                @if($setting->email_based_otp)
                                    <option value="{{ ManageStatus::AUTHORIZATION_MODE_EMAIL }}">@lang('Email')</option>
                                @endif

                                @if($setting->sms_based_otp)
                                    <option value="{{ ManageStatus::AUTHORIZATION_MODE_SMS }}">@lang('SMS')</option>
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn--sm btn--base w-100">
                            @lang('Submit')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            $(function () {
                $('#authorizationModal').on('hidden.bs.modal', function () {
                    $('#authorizationMode').val('@lang("Select One")').niceSelect('update')
                })
            })
        })(jQuery)
    </script>
@endpush
