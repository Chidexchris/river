@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="custom--card h-auto">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="title">@lang('Referral Commission Overview')</h3>

                        @can('change referral settings status')
                            @if($setting->referral_system)
                                <form action="{{ route('admin.referral.settings.status') }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn--sm btn--warning">
                                        @lang('Inactive')
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.referral.settings.status') }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn--sm btn--success">
                                        @lang('Active')
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                    <div class="card-body">
                        <h3 class="divider-title"><span>@lang('Referral Commission Count')</span></h3>
                        <div class="input--group mb-4">
                            <input type="number" min="0" class="form--control" id="referralCommissionCount" value="{{ $setting->referral_commission_count }}" placeholder="3" required>
                            <span class="input-group-text">@lang('Times')</span>
                        </div>
                        <h3 class="divider-title"><span>@lang('Existing Levels')</span></h3>

                        @if(count($referrals))
                            <ul class="list--group mb-4">
                                @foreach($referrals as $referral)
                                    <li class="list-group-item d-flex justify-content-between fw-semibold">
                                        @lang('Level'){{ ' #' . $referral->level }}<span class="fw-bold">{{ $referral->percentage . '%' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            @include('partials.noData')
                        @endif

                        <h3 class="divider-title"><span>@lang('Generate Level')</span></h3>
                        <form class="level-generation-form input--group">
                            <input type="number" class="form--control" placeholder="@lang('How many level do you want?')">
                            <button type="submit" class="btn btn--base">@lang('Generate')</button>
                        </form>
                        <div class="new-referral-levels pt-3">
                            <p class="text--warning fw-bold text-center">@lang('The existing configuration will be replaced when a new one is generated')</p>
                            <form action="" method="POST">
                                @csrf
                                <input type="hidden" name="referral_commission_count" value="{{ $setting->referral_commission_count }}">
                                <div class="new-referral-form d-flex flex-column gap-3 mb-3"></div>

                                @can('update referral settings')
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('#referralCommissionCount').on('keyup', function (event) {
                    $('input[name="referral_commission_count"]').val(event.target.value)
                })

                $('.level-generation-form').on('submit', function (e) {
                    e.preventDefault()

                    let levelCount = $(this).find('input').val()
                    let newReferralLevels = $(this).siblings('.new-referral-levels')

                    newReferralLevels.removeClass('d-none')

                    // clear any existing levels before adding new ones
                    newReferralLevels.find('.new-referral-form').empty().addClass('mt-3')

                    for (let i = 1; i <= levelCount; i++) {
                        newReferralLevels.find('.new-referral-form').append(`
                            <div class="new-referral-form-group d-flex gap-3">
                                <div class="input--group flex-grow-1">
                                    <span class="input-group-text">@lang('Level') #<span class="level-count">${i}</span></span>
                                    <input type="number" step="0.01" min="0" class="form--control form--control--sm" name="percentage[]" placeholder="@lang('Enter Commission Percentage')" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <button type="button" class="btn btn--sm btn--danger px-2 flex-shrink-0 remove-new-level">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        `)
                    }

                    $(this).find('input').val('')
                })

                // handle click event to remove a level and re-index
                $(document).on('click', '.remove-new-level', function () {
                    let referralForm = $(this).closest('.new-referral-form')

                    $(this).closest('.new-referral-form-group').remove()

                    updateLevelCounts(referralForm)
                    checkIfNoLevelsLeft(referralForm)
                })

                // function to re-index the level counts within a specific form
                function updateLevelCounts(referralForm) {
                    referralForm.find('.level-count').each(function (index) {
                        $(this).text(index + 1)
                    })
                }

                // function to check if there are no levels left within a specific form
                function checkIfNoLevelsLeft(referralForm) {
                    if (referralForm.find('.new-referral-form-group').length === 0) {
                        referralForm.removeClass('mt-3')
                    }
                }
            })
        })(jQuery)
    </script>
@endpush
