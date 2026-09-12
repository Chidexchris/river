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
                                <td>{{ __($fds->plan_name) }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('FDS No.')</span></td>
                                <td>{{ $fds->scheme_code }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Interest Rate')</span></td>
                                <td>{{ showAmount($fds->interest_rate) . '%' }}</td>
                            </tr>
                            <tr>
                                <td><span class="fw-bold">@lang('Deposited Amount')</span></td>
                                <td>{{ $setting->cur_sym . showAmount($fds->deposit_amount) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-bold">@lang('Interest Payout Interval')</span>
                                </td>
                                <td>{{ $fds->interest_payout_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $fds->interest_payout_interval)) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-bold">@lang('Per Installment Profit')</span>
                                </td>
                                <td class="text--base">{{ $setting->cur_sym . showAmount($fds->per_installment) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-bold">@lang('Total Profit Earned')</span>
                                </td>
                                <td>{{ $setting->cur_sym . showAmount($fds->profit_amount) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-bold">@lang('Locked Until')</span>
                                </td>
                                <td>{{ showDateTime($fds->locked_until, 'd M, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @php
            $columns = [
                [
                    'label'    => 'Profit Disbursement Date',
                    'callback' => function ($installment) {
                        return showDateTime($installment->installment_date, 'd M, Y');
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
