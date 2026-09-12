<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@lang('Account Statement')</title>

        <style>
            body {
                padding: 0;
                margin: 0;
                font-family: Courier;
                font-size: 15px;
                color: #474747;
            }

            img {
                max-width: 100%;
            }

            .header {
                display: flex !important;
                justify-content: space-between;
                padding-bottom: 30px;
                border-bottom: 1px solid #EDEDED;
                margin-bottom: 30px;
            }

            .header .logo {
                width: 180px;
                margin-bottom: 15px;
            }

            .header .left,
            .header .right {
                width: calc(50% - 25px);
                display: inline-block;
            }

            .header .right {
                float: right;
            }

            .header__txt {
                line-height: 1.4;
                margin: 0 0 10px;
            }

            .header table {
                width: 100%;
            }

            .header table td {
                padding: 5px 0;
            }

            .header table td:first-child {
                width: 35%;
            }

            .header table td:nth-child(2) {
                width: 3%;
                padding: 5px;
            }

            .header table strong {
                font-weight: 700;
            }

            .title {
                text-align: center;
                margin: 0 0 25px;
                font-size: 14px;
            }

            .table {
                width: 100%;
                text-align: left;
                border-collapse: collapse;
                margin-bottom: 40px;
            }

            .table th {
                background: #EDEDED;
                text-align: left !important;
            }

            .table td {
                font-size: 13px;
                line-height: 1.6;
            }

            .table th,
            .table td {
                border-top: 1px solid #EDEDED;
                padding: 11px 9px;
            }

            .table th:first-child,
            .table td:first-child {
                padding-left: 15px;
            }

            .table td:nth-child(2) {
                width: 230px;
            }

            .table th:last-child,
            .table td:last-child {
                padding-right: 15px;
            }

            .footer {
                text-align: center;
                font-size: 15px;
                line-height: 1.3;
                font-weight: 700;
                opacity: 0.7;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="left">
                <div class="logo">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="{{ "$setting->site_name Logo" }}">
                </div>
                <p class="header__txt">{{ $companyInfo['Address'] ?? 'Address not provided' }}</p>
                <table>
                    <tbody>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>:</td>
                            <td>{{ $companyInfo['Email Address'] ?? 'Email not provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone</strong></td>
                            <td>:</td>
                            <td>{{ $companyInfo['Phone'] ?? 'Phone not provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Website</strong></td>
                            <td>:</td>
                            <td>{{ url('/') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="right">
                <p class="header__txt"><strong>{{ strtoupper($user->fullname) }}</strong></p>
                <p class="header__txt">
                    {{ implode(', ', array_filter([
                        $user->address?->address,
                        $user->address?->city,
                        $user->address?->state,
                        $user->address?->zip,
                        $user->country_name
                    ])) }}
                </p>
                <table>
                    <tbody>
                        <tr>
                            <td><strong>Account No</strong></td>
                            <td>:</td>
                            <td>{{ $user->account_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Branch</strong></td>
                            <td>:</td>
                            <td>{{ $user->branch?->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Username</strong></td>
                            <td>:</td>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td><strong>Issue Date</strong></td>
                            <td>:</td>
                            <td>{{ now()->format('F d, Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h2 class="title">{{ strtoupper("Statement of Account for the Period: $formattedDates[0] to $formattedDates[1]") }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>DATE</th>
                    <th>DETAILS</th>
                    <th>CREDIT</th>
                    <th>DEBIT</th>
                    <th>BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ showDateTime($transaction->created_at, 'd-M-Y') }}</td>
                        <td>{{ $transaction->details }}</td>
                        <td>{{ $transaction->trx_type == '+' ? $setting->cur_sym . showAmount($transaction->amount) : '-' }}</td>
                        <td>{{ $transaction->trx_type == '-' ? $setting->cur_sym . showAmount($transaction->amount) : '-' }}</td>
                        <td>{{ $setting->cur_sym . showAmount($transaction->post_balance) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>{{ $setting->cur_sym . showAmount($creditTotal) }}</strong></td>
                    <td><strong>{{ $setting->cur_sym . showAmount($debitTotal) }}</strong></td>
                    <td><strong>{{ $setting->cur_sym . showAmount($transactions->last()->post_balance) }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div class="footer">{{ "Powered by $setting->site_name" }}</div>
    </body>
</html>
