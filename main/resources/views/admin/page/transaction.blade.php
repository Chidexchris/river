@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-body">
                <form class="row g-xl-4 g-3 align-items-end">
                    <div class="col-xl-3 col-sm-6">
                        <label class="form--label">@lang('TRX/Username')</label>
                        <input type="text" class="form--control" name="search" value="{{ request()->search }}">
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <label class="form--label">@lang('Type')</label>
                        <select class="form--control form-select" name="trx_type">
                            <option value="">@lang('All')</option>
                            <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                            <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <label class="form--label">@lang('Remark')</label>
                        <select class="form--control form-select select-2" name="remark">
                            <option value="">@lang('Any')</option>

                            @foreach($remarks as $remark)
                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                    {{ __(keyToTitle($remark->remark)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <label class="form--label">@lang('Date')</label>
                        <input type="search" class="form--control datepicker-here" name="date" value="{{ request()->date }}" data-range="true" data-multiple-dates-separator=" - " data-language="en" placeholder="@lang('Start Date - End Date')" autocomplete="off">
                    </div>
                    <div class="col-xl-2">
                        <button type="submit" class="btn btn--base w-100">
                            <i class="ti ti-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('TRX')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('After Balance')</th>
                        <th>@lang('Details')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ $transaction->user->fullname }}</p>
                                    <p class="fw-semibold">
                                        <a href="{{ appendQuery('search', $transaction->user->username) }}">
                                            {{ $transaction->user->username }}
                                        </a>
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $transaction->trx }}</span>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($transaction->created_at) }}</p>
                                    <p>{{ diffForHumans($transaction->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                <span class="@if($transaction->trx_type == '+') text--success @else text--danger @endif">
                                    {{ $transaction->trx_type . ' ' . showAmount($transaction->amount) . ' ' . __($setting->site_cur) }}
                                </span>
                            </td>
                            <td>
                                <span class="text--danger">{{ showAmount($transaction->charge) . ' ' . __($setting->site_cur) }}</span>
                            </td>
                            <td>{{ showAmount($transaction->post_balance) . ' ' . __($setting->site_cur) }}</td>
                            <td>
                                <span title="{{ $transaction->details }}">{{ strLimit($transaction->details, 25) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.transaction.edit', $transaction->id) }}" class="btn btn--sm btn-outline--base">
                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </a>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            {{ paginateLinks($transactions) }}
        @endif
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict"

            let datePicker = $('.datepicker-here')

            datePicker.on('input keyup keydown keypress', function () {
                return false
            })

            if (!datePicker.val()) datePicker.datepicker()
        })(jQuery)
    </script>
@endpush
