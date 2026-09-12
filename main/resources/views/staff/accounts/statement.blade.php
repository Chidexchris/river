@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-body">
                <form action="{{ route('staff.accounts.fetch.statement', $account) }}" method="get" class="row g-xl-4 g-3 align-items-end">
                    <div class="col-xl-3 col-sm-6">
                        <label class="form--label">@lang('Date')</label>
                        <input type="search" class="form--control date-picker" name="date" value="{{ request('date') }}" data-range="true" data-multiple-dates-separator=" - " data-language="en" placeholder="@lang('Start Date - End Date')" required autocomplete="off">
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <label class="form--label">@lang('Transaction Type')</label>
                        <select class="form--control wide" name="trx_type">
                            <option value="">@lang('All')</option>
                            <option value="+" @selected(request('trx_type') == '+')>@lang('Plus')</option>
                            <option value="-" @selected(request('trx_type') == '-')>@lang('Minus')</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <label class="form--label">@lang('From Amount')</label>
                        <input type="number" step="any" min="0" class="form--control" name="from_amount" placeholder="@lang('Enter Amount')" value="{{ request('from_amount') }}">
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <label class="form--label">@lang('To Amount')</label>
                        <input type="number" step="any" min="0" class="form--control" name="to_amount" placeholder="@lang('Enter Amount')" value="{{ request('to_amount') }}">
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

    @isset($transactions)
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <h5 class="mb-0">@lang('Financial Overview')</h5>

                @if($transactions->count())
                    <form action="{{ route('staff.accounts.export.statement', $account) }}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <button type="submit" class="btn btn--sm btn--base">
                            @lang('Export')
                        </button>
                    </form>
                @endif
            </div>

            @include('staff.partials.transactionTable')

            @if ($transactions->hasPages())
                {{ paginateLinks($transactions) }}
            @endif
        </div>
    @endisset
@endsection

@push('breadcrumb')
    <a href="{{ route('staff.accounts.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

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
            'use strict'

            $(function () {
                let datePicker = $('.date-picker')

                datePicker.on('input keyup keydown keypress', function () {
                    return false
                })

                datePicker.datepicker()
            })
        })(jQuery)
    </script>
@endpush
