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
                                <td>{{ __($loan->plan_name) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Loan No.')</span></td>
                                <td>{{ $loan->scheme_code }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($loan->amount_requested) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Per Installment')</span></td>
                                <td><span class="text--base">{{ $setting->cur_sym . showAmount($loan->per_installment) }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Installment Interval')</span></td>
                                <td>{{ $loan->installment_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $loan->installment_interval)) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Total Installment')</span></td>
                                <td>{{ $loan->total_installment }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Given Installment')</span></td>
                                <td>{{ $loan->given_installment }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Payable Amount')</span></td>
                                <td><span class="text--base">{{ $setting->cur_sym . showAmount($loan->per_installment * $loan->total_installment) }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Approval Date')</span></td>
                                <td>{{ is_null($loan->approved_at) ? trans('N/A') : showDateTime($loan->approved_at, 'd M, Y') }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Late Fee')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($loan->per_installment_late_fee) . '/' . trans('Day') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <small class="text--danger d-block text-start">
                                        {{ trans('If an installment is delayed by') . ' ' . $loan->delay_duration . ' ' . trans('or more days, a late fee will be applied for each day of delay.') }}
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
