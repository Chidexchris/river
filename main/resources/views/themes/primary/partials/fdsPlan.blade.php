<div class="col-xl-4 col-lg-5 col-md-6" @if(request()->routeIs('home')) data-aos="fade-up" data-aos-duration="1500" @endif>
    <div class="pricing__card-2">
        <div class="pricing__card-2__top" data-mask-image="{{ asset($activeThemeTrue . 'images/pricing-vector.png') }}">
            <span class="pricing__card-2__icon">@php echo $fdsPlan->icon @endphp</span>
            <div class="pricing__card-2__txt">
                <span class="pricing__card-2__name">{{ __($fdsPlan->name) }}</span>
                <h3 class="pricing__card-2__price">{{ $fdsPlan->interest_rate . '%' }} <span>{{ '/' . $fdsPlan->interest_payout_interval . ' ' . trans('Days') }}</span></h3>
            </div>
        </div>
        <div class="pricing__card-2__middle">
            <ul>
                <li>@lang('Interest Rate') <span>{{ $fdsPlan->interest_rate . '%' }}</span></li>
                <li>@lang('Earn Profit Every') <span>{{ $fdsPlan->interest_payout_interval . ' ' . trans('Days') }}</span></li>
                <li>@lang('Lock-In Period') <span>{{ $fdsPlan->lock_in_period . ' ' . trans('Days') }}</span></li>
                <li>@lang('Min Deposit Amount') <span>{{ $setting->cur_sym . showAmount($fdsPlan->minimum_amount) }}</span></li>
                <li>@lang('Max Deposit Amount') <span>{{ $setting->cur_sym . showAmount($fdsPlan->maximum_amount) }}</span></li>
            </ul>
        </div>
        <div class="pricing__card-2__bottom">
            <button type="button" class="btn btn--base btn-choose" data-bs-toggle="modal" data-bs-target="#fdsAmountModal" data-plan="{{ $fdsPlan->id }}">
                @lang('Choose Plan')
            </button>
        </div>
    </div>
</div>

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="fdsAmountModal" tabindex="-1" aria-labelledby="fdsAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="fdsAmountModalLabel">@lang('Specify Your Investment Amount')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.fds.plan.choose') }}" method="post">
                        @csrf
                        <input type="hidden" id="fdsPlanId" name="fds_plan" value="">
                        <div class="form-group">
                            <label for="fdsAmount" class="form--label required">@lang('Deposit Amount')</label>
                            <input type="number" step="any" min="0" id="fdsAmount" class="form--control form--control--sm" name="fds_amount" required>
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
            'use strict'

            $(function () {
                $('.btn-choose').on('click', function () {
                    $('#fdsPlanId').val($(this).data('plan'))
                })

                $('#fdsAmountModal').on('hidden.bs.modal', function () {
                    $('#fdsAmount').val('')
                })
            })
        })(jQuery)
    </script>
@endpush
