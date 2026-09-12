@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.other.banks.update', $otherBank) }}" method="post" class="row g-4">
            @csrf
            <div class="col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-sm-6">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" value="{{ old('name', $otherBank->name) }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form--label required">@lang('Processing Time')</label>
                                <input type="text" class="form--control" name="processing_time" value="{{ old('processing_time', $otherBank->processing_time) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Per Transaction')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Minimum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="per_transaction_min_amount" value="{{ old('per_transaction_min_amount', getAmount($otherBank->per_transaction_min_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="per_transaction_max_amount" value="{{ old('per_transaction_max_amount', getAmount($otherBank->per_transaction_max_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Daily Transaction')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="daily_transaction_max_amount" value="{{ old('daily_transaction_max_amount', getAmount($otherBank->daily_transaction_max_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Transaction Limit')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="daily_transaction_limit" value="{{ old('daily_transaction_limit', $otherBank->daily_transaction_limit) }}" required>
                                    <span class="input-group-text">@lang('Times')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Monthly Transaction')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Maximum Amount')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="monthly_transaction_max_amount" value="{{ old('monthly_transaction_max_amount', getAmount($otherBank->monthly_transaction_max_amount)) }}" required>
                                    <span class="input-group-text">{{ $setting->site_cur }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Transaction Limit')</label>
                                <div class="input--group">
                                    <input type="number" min="0" class="form--control" name="monthly_transaction_limit" value="{{ old('monthly_transaction_limit', $otherBank->monthly_transaction_limit) }}" required>
                                    <span class="input-group-text">@lang('Times')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">
                            @lang('Transaction Charge') <span title="@lang('A charge will be applied to each transaction. Leave these fields blank if you do not wish to apply any charges.')"><i class="ti ti-info-circle fz-2"></i></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-lg-4 g-3">
                            <div class="col-12">
                                <label class="form--label">@lang('Fixed Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="fixed_charge" value="{{ old('fixed_charge', getAmount($otherBank->fixed_charge)) }}">
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('Percentage Charge')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="percentage_charge" value="{{ old('percentage_charge', getAmount($otherBank->percentage_charge)) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">
                            @lang('Instruction') <span title="@lang('This instruction will be displayed to users while they are transferring money to this bank.')"><i class="ti ti-info-circle fz-2"></i></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <textarea class="form--control ck-editor" name="instruction">{{ old('instruction', $otherBank->instruction) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.formData')
        </form>
    </div>

    <x-formGenerator />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.other.banks.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                ClassicEditor
                    .create(document.querySelector('.ck-editor'), {
                        toolbar: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            '|',
                            'bulletedList',
                            'numberedList'
                        ],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            ]
                        },
                    })
                    .then(editor => {
                        window.editor = editor
                    })
                    .catch(error => {
                        console.error(error)
                    })
            })
        })(jQuery)
    </script>
@endpush
