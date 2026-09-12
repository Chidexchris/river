@extends('admin.layouts.master')

@section('master')
    <div class="col-lg-4">
        <div class="custom--card h-auto">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="title">@lang('DPS Information')</h3>

                @php echo $dps->status_badge @endphp
            </div>
            <div class="card-body">
                <table class="table table-flush">
                    <tbody>
                        <tr>
                            <td><strong>@lang('Account No.')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <a href="{{ route('admin.user.details', $dps->user->id) }}">
                                    {{ $dps->user->account_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Plan')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ __($dps->plan_name) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('DPS No.')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $dps->scheme_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Per Installment')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start text--base">{{ $setting->cur_sym . showAmount($dps->per_installment) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Installment Interval')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $dps->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $dps->installment_interval)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Total Installment')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $dps->total_installment }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Given Installment')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $dps->given_installment }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Total Deposit')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($dps->total_deposit_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Interest Rate')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showAmount($dps->interest_rate) . '%' }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Profit Amount')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($dps->profit_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Maturity Amount')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($dps->maturity_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Late Fee')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($dps->per_installment_late_fee) . '/' . trans('Day') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <small class="text--warning d-block text-start">
                                    {{ trans('If an installment is delayed by') . ' ' . $dps->delay_duration . ' ' . trans('or more days, a late fee will be applied for each day of delay.') }}
                                </small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php
        $columns = [
            [
                'label'    => 'Installment Date',
                'callback' => function ($installment) {
                    $class = $installment->installment_date->lt(today()) && is_null($installment->given_at) ? 'text--danger' : '';

                    return "<span class='$class'>" . showDateTime($installment->installment_date, 'd M, Y') . "</span>";
                }
            ],
            [
                'label'    => 'Paid On',
                'callback' => function ($installment) {
                    return is_null($installment->given_at) ? trans('Not Yet') : showDateTime($installment->given_at, 'd M, Y');
                }
            ],
            [
                'label'    => 'Delay',
                'callback' => function ($installment) {
                    return is_null($installment->delay) ? '...' : $installment->delay . ' ' . ($installment->delay > 1 ? trans('Days') : trans('Day'));
                }
            ],
        ];
    @endphp

    <div class="col-lg-8">
        <x-installmentTable :$installments :$columns />
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.dps.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush
