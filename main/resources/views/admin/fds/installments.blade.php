@extends('admin.layouts.master')

@section('master')
    <div class="col-lg-4">
        <div class="custom--card h-auto">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="title">@lang('FDS Information')</h3>

                @php echo $fds->status_badge @endphp
            </div>
            <div class="card-body">
                <table class="table table-flush">
                    <tbody>
                        <tr>
                            <td><strong>@lang('Account No.')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <a href="{{ route('admin.user.details', $fds->user->id) }}">
                                    {{ $fds->user->account_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Plan')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ __($fds->plan_name) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('FDS No.')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $fds->scheme_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Interest Rate')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showAmount($fds->interest_rate) . '%' }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Deposited Amount')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($fds->deposit_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Interest Payout Interval')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $fds->interest_payout_interval . ' ' . trans(\Illuminate\Support\Str::plural('Day', $fds->interest_payout_interval)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Per Installment Profit')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start text--base">{{ $setting->cur_sym . showAmount($fds->per_installment) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Total Profit Paid')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $setting->cur_sym . showAmount($fds->profit_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Locked Until')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showDateTime($fds->locked_until, 'd M, Y') }}</td>
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

    <div class="col-lg-8">
        <x-installmentTable :$installments :$columns />
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.fds.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush
