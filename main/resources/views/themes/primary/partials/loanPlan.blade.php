@php $lsContent = getSiteData('ls.content', true) @endphp

<div class="col-xl-4 col-lg-5 col-md-6" @if(request()->routeIs('home')) data-aos="fade-up" data-aos-duration="1500" @endif>
    <div class="pricing__card-3">
        <div class="pricing__card-3__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/ls/' . $lsContent->data_info->background_image, '500x610') }}"></div>
        <div class="pricing__card-3__top">
            <span class="pricing__card-3__name">{{ __($loanPlan->name) }}</span>
            <span class="pricing__card-3__icon">@php echo $loanPlan->icon @endphp</span>
            <h3 class="pricing__card-3__price">{{ showAmount($loanPlan->installment_rate) . '%' }} <span>{{ '/' . $loanPlan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loanPlan->installment_interval)) }}</span></h3>
        </div>
        <div class="pricing__card-3__middle">
            <button type="button" class="btn btn--base btn-choose w-100" data-bs-toggle="modal" data-bs-target="#loanAmountModal" data-plan="{{ $loanPlan->id }}">
                @lang('Choose Plan')
            </button>
        </div>
        <div class="pricing__card-3__bottom">
            <ul>
                <li>@lang('Minimum Amount') <span>{{ $setting->cur_sym . showAmount($loanPlan->minimum_amount) }}</span></li>
                <li>@lang('Maximum Amount') <span>{{ $setting->cur_sym . showAmount($loanPlan->maximum_amount) }}</span></li>
                <li>@lang('Installment Rate') <span>{{ showAmount($loanPlan->installment_rate) . '%' }}</span></li>
                <li>@lang('Installment Interval') <span>{{ $loanPlan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loanPlan->installment_interval)) }}</span></li>
                <li>@lang('Total Installments') <span>{{ $loanPlan->total_installments }}</span></li>
            </ul>
        </div>
    </div>
</div>

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="loanAmountModal" tabindex="-1" aria-labelledby="loanAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="loanAmountModalLabel">@lang('Specify Your Loan Amount')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.loan.plan.choose') }}" method="post">
                        @csrf
                        <input type="hidden" id="loanPlanId" name="loan_plan" value="">
                        <div class="form-group">
                            <label for="loanAmount" class="form--label required">@lang('Loan Amount')</label>
                            <input type="number" step="any" min="0" id="loanAmount" class="form--control form--control--sm" name="loan_amount" required>
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
                    $('#loanPlanId').val($(this).data('plan'))
                })

                $('#loanAmountModal').on('hidden.bs.modal', function () {
                    $('#loanAmount').val('')
                })
            })
        })(jQuery)
    </script>
@endpush
