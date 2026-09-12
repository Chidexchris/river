@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-10">
            <div class="custom--card h-auto">
                <div class="card-body">
                    <table class="table table-borderless no-shadow">
                        <tbody>
                            <tr>
                                <td><span class="fw-bold">@lang('Plan')</span></td>
                                <td>{{ __($dps->plan_name) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('DPS No.')</span></td>
                                <td>{{ $dps->scheme_code }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Per Installment')</span></td>
                                <td><span class="text--base">{{ $setting->cur_sym . showAmount($dps->per_installment) }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Installment Interval')</span></td>
                                <td>{{ $dps->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $dps->installment_interval)) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Total Installment')</span></td>
                                <td>{{ $dps->total_installment }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Given Installment')</span></td>
                                <td>{{ $dps->given_installment }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Total Deposit')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($dps->total_deposit_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Interest Rate')</span></td>
                                <td>{{ $dps->interest_rate . '%' }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Profit Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($dps->profit_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Maturity Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($dps->maturity_amount) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Late Fee')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($dps->per_installment_late_fee) . '/' . trans('Day') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <small class="text--danger d-block text-start">
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

        <div class="col-xl-8 col-lg-7 col-md-10">
            <div class="custom--card h-auto">
                <div class="card-body">
                    <x-installmentTable class="no-shadow" :$installments :$columns />
                </div>
            </div>
        </div>
    </div>
@endsection
