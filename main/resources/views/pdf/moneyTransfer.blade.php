<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@lang('Money Transfer')</title>

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
                margin-bottom: 25px;
            }

            .header .logo {
                width: 180px;
                margin-bottom: 15px;
            }

            .header .left {
                width: calc(50% - 25px);
                display: inline-block;
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
                border-right: 1px solid #EDEDED;
                border-left: 1px solid #EDEDED;
            }

            .table th {
                background: #EDEDED;
                text-align: left !important;
            }

            .table td {
                font-size: 13px;
                line-height: 1.6;
            }

            .table td p {
                line-height: 1;
            }

            .table th,
            .table td {
                border-top: 1px solid #EDEDED;
                padding: 11px 9px;
            }

            .table tr:last-child {
                border-bottom: 1px solid #EDEDED;
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
        </div>
        <h2 class="title">{{ strtoupper('Money Transfer Information') }}</h2>
        <table class="table">
            <tbody>
                <tr>
                    <td><strong>Transaction Number</strong></td>
                    <td>{{ "#$moneyTransfer->trx" }}</td>
                </tr>
                <tr>
                    <td><strong>From Account</strong></td>
                    <td>
                        <p>{{ $user->fullname }}</p>
                        <p>{{ $user->account_number }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong>To Account</strong></td>
                    <td>
                        <p>{{ $moneyTransfer->account_name ?? $moneyTransfer->beneficiary->details['account_name'] }}</p>
                        <p>{{ $moneyTransfer->account_number ?? $moneyTransfer->beneficiary->details['account_number'] }}</p>
                        <p>{{ is_null($moneyTransfer->beneficiary_id) ? trans('Wire Transfer') : $moneyTransfer->bank_name }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong>Amount</strong></td>
                    <td>{{ showAmount($moneyTransfer->amount) . " $setting->site_cur" }}</td>
                </tr>
                <tr>
                    <td><strong>Charge</strong></td>
                    <td>{{ showAmount($moneyTransfer->charge) . " $setting->site_cur" }}</td>
                </tr>
                <tr>
                    <td><strong>Date</strong></td>
                    <td>{{ showDateTime($moneyTransfer->created_at, 'M d, Y - h:i A') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="footer">{{ "Powered by $setting->site_name" }}</div>
    </body>
</html>
