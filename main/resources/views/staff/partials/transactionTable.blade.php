<div class="table-responsive scroll">
    <table class="table table--striped table-borderless table--responsive--sm">
        <thead>
            <tr>
                <th>@lang('TRX')</th>
                <th>@lang('Account No.')</th>
                <th>@lang('Account Name')</th>

                @if(isManager())
                    <th>@lang('Account Officer')</th>
                @endif

                <th>@lang('Initiated')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Remark')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->trx }}</td>
                    <td>{{ $transaction->user->account_number }}</td>
                    <td>{{ __($transaction->user->fullname) }}</td>

                    @if(isManager())
                        <td>{{ __($transaction->staff->name) }}</td>
                    @endif

                    <td>
                        <span>
                            <span class="d-block">{{ showDateTime($transaction->created_at) }}</span>
                            <span class="d-block">{{ diffForHumans($transaction->created_at) }}</span>
                        </span>
                    </td>
                    <td>
                        <span class="@if ($transaction->trx_type == '+') text--success @else text--danger @endif">
                            {{ $transaction->trx_type == '+' ? '+' : '-' }} {{ showAmount($transaction->amount) . ' ' . __($setting->site_cur) }}
                        </span>
                    </td>
                    <td>{{ __(keyToTitle($transaction->remark)) }}</td>
                </tr>
            @empty
                @include('partials.noData')
            @endforelse
        </tbody>
    </table>
</div>
