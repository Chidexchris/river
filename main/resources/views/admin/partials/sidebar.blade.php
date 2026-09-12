<div class="main-sidebar">
    <form class="header__search d-md-none">
        <span class="header__search__icon"><i class="ti ti-search"></i></span>
        <input type="search" class="header__search__input" placeholder="@lang('Search')..." id="searchInput" autocomplete="off">
        <ul class="search-list d-none"></ul>
    </form>
    <ul class="sidebar-menu scroll">
        @can('access dashboard')
            <li class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ navigationActive('admin.dashboard', 2) }}">
                    <span class="nav-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="sidebar-txt">@lang('Dashboard')</span>
                </a>
            </li>
        @endcan

        <li class="sidebar-item">
            <a href="{{ route('admin.chat.index') }}" class="sidebar-link {{ navigationActive('admin.chat*', 2) }}">
                <span class="nav-icon"><i class="ti ti-message-2"></i></span>
                <span class="sidebar-txt">@lang('Customer Chat')</span>
            </a>
        </li>

        @canany(['view all automated gateways', 'view all manual gateways'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.gateway*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-credit-card"></i></span>
                    <span class="sidebar-txt">@lang('Payment Methods')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all automated gateways')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.gateway.automated.index') }}" class="sidebar-link {{ navigationActive('admin.gateway.automated*', 1) }}">
                                @lang('Automated')
                            </a>
                        </li>
                    @endcan

                    @can('view all manual gateways')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.gateway.manual.index') }}" class="sidebar-link {{ navigationActive('admin.gateway.manual*', 1) }}">
                                @lang('Manual')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view all roles')
            <li class="sidebar-item">
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ navigationActive('admin.roles*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-shield-lock"></i></span>
                    <span class="sidebar-txt">@lang('Role & Permission')</span>
                </a>
            </li>
        @endcan

        @can('view all admins')
            <li class="sidebar-item">
                <a href="{{ route('admin.staffs.index') }}" class="sidebar-link {{ navigationActive('admin.staffs.index', 2) }}">
                    <span class="nav-icon"><i class="ti ti-user-cog"></i></span>
                    <span class="sidebar-txt">@lang('Admin Staffs')</span>
                </a>
            </li>
        @endcan

        @canany(['view all users', 'view active users', 'view banned users', 'view kyc pending users', 'view kyc unconfirmed users', 'view email unconfirmed users', 'view mobile unconfirmed users'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.user*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-users"></i>

                        @if($bannedUsersCount || $emailUnconfirmedUsersCount || $mobileUnconfirmedUsersCount || $kycUnconfirmedUsersCount || $kycPendingUsersCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Users')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.index') }}" class="sidebar-link {{ navigationActive('admin.user.index', 1) }}">
                                @lang('All')
                            </a>
                        </li>
                    @endcan

                    @can('view active users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.active') }}" class="sidebar-link {{ navigationActive('admin.user.active', 1) }}">
                                @lang('Active')
                            </a>
                        </li>
                    @endcan

                    @can('view banned users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.banned') }}" class="sidebar-link {{ navigationActive('admin.user.banned', 1) }}">
                                @lang('Banned')

                                @if ($bannedUsersCount)
                                    <span class="badge badge--danger rounded-1">{{ $bannedUsersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view kyc pending users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.kyc.pending') }}" class="sidebar-link {{ navigationActive('admin.user.kyc.pending', 1) }}">
                                @lang('KYC Pending')

                                @if ($kycPendingUsersCount)
                                    <span class="badge badge--danger rounded-1">{{ $kycPendingUsersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view kyc unconfirmed users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.kyc.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.kyc.unconfirmed', 1) }}">
                                @lang('KYC Unconfirmed')

                                @if ($kycUnconfirmedUsersCount)
                                    <span class="badge badge--danger rounded-1">{{ $kycUnconfirmedUsersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view email unconfirmed users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.email.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.email.unconfirmed', 1) }}">
                                @lang('Email Unconfirmed')

                                @if ($emailUnconfirmedUsersCount)
                                    <span class="badge badge--danger rounded-1">{{ $emailUnconfirmedUsersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view mobile unconfirmed users')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.mobile.unconfirmed', 1) }}">
                                @lang('Mobile Unconfirmed')

                                @if ($mobileUnconfirmedUsersCount)
                                    <span class="badge badge--danger rounded-1">{{ $mobileUnconfirmedUsersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view all branches')
            <li class="sidebar-item">
                <a href="{{ route('admin.branches.index') }}" class="sidebar-link {{ navigationActive('admin.branches*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-sitemap"></i></span>
                    <span class="sidebar-txt">@lang('Branches')</span>
                </a>
            </li>
        @endcan

        @can('view all branch staffs')
            <li class="sidebar-item">
                <a href="{{ route('admin.branch.staffs.index') }}" class="sidebar-link {{ navigationActive('admin.branch.staffs*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-users-group"></i></span>
                    <span class="sidebar-txt">@lang('Branch Staffs')</span>
                </a>
            </li>
        @endcan

        @canany(['view all dps plans', 'view all dps', 'view running dps', 'view matured dps', 'view closed dps'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.dps*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-moneybag"></i>
                    </span>
                    <span class="sidebar-txt">@lang('DPS')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all dps plans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.plans') }}" class="sidebar-link {{ navigationActive('admin.dps.plans', 1) }}">
                                @lang('Plans')
                            </a>
                        </li>
                    @endcan

                    @can('view all dps')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.index') }}" class="sidebar-link {{ navigationActive('admin.dps.index', 1) }}">
                                @lang('All DPS')
                            </a>
                        </li>
                    @endcan

                    @can('view running dps')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.running') }}" class="sidebar-link {{ navigationActive('admin.dps.running', 1) }}">
                                @lang('Running DPS')
                            </a>
                        </li>
                    @endcan

                    @can('view late installment dps')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.late.installment') }}" class="sidebar-link {{ navigationActive('admin.dps.late.installment', 1) }}">
                                @lang('Late Installment DPS')
                            </a>
                        </li>
                    @endcan

                    @can('view matured dps')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.matured') }}" class="sidebar-link {{ navigationActive('admin.dps.matured', 1) }}">
                                @lang('Matured DPS')
                            </a>
                        </li>
                    @endcan

                    @can('view closed dps')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.dps.closed') }}" class="sidebar-link {{ navigationActive('admin.dps.closed', 1) }}">
                                @lang('Closed DPS')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['view all fds plans', 'view all fds', 'view running fds', 'view closed fds'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.fds*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-cash-register"></i>
                    </span>
                    <span class="sidebar-txt">@lang('FDS')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all fds plans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.fds.plans') }}" class="sidebar-link {{ navigationActive('admin.fds.plans', 1) }}">
                                @lang('Plans')
                            </a>
                        </li>
                    @endcan

                    @can('view all fds')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.fds.index') }}" class="sidebar-link {{ navigationActive('admin.fds.index', 1) }}">
                                @lang('All FDS')
                            </a>
                        </li>
                    @endcan

                    @can('view running fds')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.fds.running') }}" class="sidebar-link {{ navigationActive('admin.fds.running', 1) }}">
                                @lang('Running FDS')
                            </a>
                        </li>
                    @endcan

                    @can('view closed fds')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.fds.closed') }}" class="sidebar-link {{ navigationActive('admin.fds.closed', 1) }}">
                                @lang('Closed FDS')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['view all loan plans', 'view all loans', 'view pending loans', 'view running loans', 'view late installment loans', 'view paid loans', 'view rejected loans'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.loan*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-cash"></i>

                        @if($pendingLoansCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Loan')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all loan plans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.plans') }}" class="sidebar-link {{ navigationActive('admin.loan.plans', 1) }}">
                                @lang('Plans')
                            </a>
                        </li>
                    @endcan

                    @can('view all loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.index') }}" class="sidebar-link {{ navigationActive('admin.loan.index', 1) }}">
                                @lang('All Loans')
                            </a>
                        </li>
                    @endcan

                    @can('view pending loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.pending') }}" class="sidebar-link {{ navigationActive('admin.loan.pending', 1) }}">
                                @lang('Pending Loans')

                                @if($pendingLoansCount)
                                    <span class="badge badge--danger rounded-1">{{ $pendingLoansCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view running loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.running') }}" class="sidebar-link {{ navigationActive('admin.loan.running', 1) }}">
                                @lang('Running Loans')
                            </a>
                        </li>
                    @endcan

                    @can('view late installment loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.late.installment') }}" class="sidebar-link {{ navigationActive('admin.loan.late.installment', 1) }}">
                                @lang('Late Installment Loans')
                            </a>
                        </li>
                    @endcan

                    @can('view paid loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.paid') }}" class="sidebar-link {{ navigationActive('admin.loan.paid', 1) }}">
                                @lang('Paid Loans')
                            </a>
                        </li>
                    @endcan

                    @can('view rejected loans')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.loan.rejected') }}" class="sidebar-link {{ navigationActive('admin.loan.rejected', 1) }}">
                                @lang('Rejected Loans')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['view all deposits', 'view pending deposits', 'view done deposits', 'view cancelled deposits'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.deposits*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-wallet"></i>

                        @if($pendingDepositsCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Deposits')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all deposits')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.deposits.index') }}" class="sidebar-link {{ navigationActive('admin.deposits.index', 1) }}">
                                @lang('All')
                            </a>
                        </li>
                    @endcan

                    @can('view pending deposits')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.deposits.pending') }}" class="sidebar-link {{ navigationActive('admin.deposits.pending', 1) }}">
                                @lang('Pending')

                                @if($pendingDepositsCount)
                                    <span class="badge badge--danger rounded-1">{{ $pendingDepositsCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view done deposits')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.deposits.done') }}" class="sidebar-link {{ navigationActive('admin.deposits.done', 1) }}">
                                @lang('Done')
                            </a>
                        </li>
                    @endcan

                    @can('view cancelled deposits')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.deposits.cancelled') }}" class="sidebar-link {{ navigationActive('admin.deposits.cancelled', 1) }}">
                                @lang('Cancelled')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['view all withdraw methods', 'view all withdrawals', 'view pending withdrawals', 'view done withdrawals', 'view cancelled withdrawals'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.withdraw*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-cash-banknote"></i>

                        @if($pendingWithdrawalsCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Withdrawals')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all withdraw methods')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.withdraw.method.index') }}" class="sidebar-link {{ navigationActive('admin.withdraw.method.index', 1) }}">
                                @lang('Methods')
                            </a>
                        </li>
                    @endcan

                    @can('view all withdrawals')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.withdrawals.index') }}" class="sidebar-link {{ navigationActive('admin.withdrawals.index', 1) }}">
                                @lang('All')
                            </a>
                        </li>
                    @endcan

                    @can('view pending withdrawals')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.withdrawals.pending') }}" class="sidebar-link {{ navigationActive('admin.withdrawals.pending', 1) }}">
                                @lang('Pending')

                                @if($pendingWithdrawalsCount)
                                    <span class="badge badge--danger rounded-1">{{ $pendingWithdrawalsCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view done withdrawals')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.withdrawals.done') }}" class="sidebar-link {{ navigationActive('admin.withdrawals.done', 1) }}">
                                @lang('Done')
                            </a>
                        </li>
                    @endcan

                    @can('view cancelled withdrawals')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.withdrawals.cancelled') }}" class="sidebar-link {{ navigationActive('admin.withdrawals.cancelled', 1) }}">
                                @lang('Cancelled')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view all transactions')
            <li class="sidebar-item">
                <a href="{{ route('admin.transaction.index') }}" class="sidebar-link {{ navigationActive('admin.transaction.index', 2) }}">
                    <span class="nav-icon"><i class="ti ti-transform"></i></span>
                    <span class="sidebar-txt">@lang('Transactions')</span>
                </a>
            </li>
        @endcan

        @can('view other banks')
            <li class="sidebar-item">
                <a href="{{ route('admin.other.banks.index') }}" class="sidebar-link {{ navigationActive('admin.other.banks*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-building-bank"></i></span>
                    <span class="sidebar-txt">@lang('Other Banks')</span>
                </a>
            </li>
        @endcan

        @can('view wire transfer settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.wire.transfer.settings') }}" class="sidebar-link {{ navigationActive('admin.wire.transfer.settings', 2) }}">
                    <span class="nav-icon"><i class="ti ti-world-share"></i></span>
                    <span class="sidebar-txt">@lang('Wire Transfer Settings')</span>
                </a>
            </li>
        @endcan

        @canany(['view all money transfers', 'view pending money transfers', 'view completed money transfers', 'view failed money transfers', 'view internal money transfers', 'view external money transfers', 'view wire transfers'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.money.transfers*', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-arrows-right-left"></i>

                        @if($pendingMoneyTransfersCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Money Transfers')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view all money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.index') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.index', 1) }}">
                                @lang('All')
                            </a>
                        </li>
                    @endcan

                    @can('view pending money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.pending') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.pending', 1) }}">
                                @lang('Pending')

                                @if($pendingMoneyTransfersCount)
                                    <span class="badge badge--danger rounded-1">{{ $pendingMoneyTransfersCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('view completed money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.completed') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.completed', 1) }}">
                                @lang('Completed')
                            </a>
                        </li>
                    @endcan

                    @can('view failed money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.failed') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.failed', 1) }}">
                                @lang('Failed')
                            </a>
                        </li>
                    @endcan

                    @can('view internal money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.internal') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.internal', 1) }}">
                                @lang('Internal')
                            </a>
                        </li>
                    @endcan

                    @can('view external money transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.external') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.external', 1) }}">
                                @lang('External')
                            </a>
                        </li>
                    @endcan

                    @can('view wire transfers')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.money.transfers.wire') }}" class="sidebar-link {{ navigationActive('admin.money.transfers.wire', 1) }}">
                                @lang('Wire')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view all contacts')
            <li class="sidebar-item">
                <a href="{{ route('admin.contact.index') }}" class="sidebar-link {{ navigationActive('admin.contact.index', 2) }}">
                    <span class="nav-icon">
                        <i class="ti ti-id"></i>

                        @if($unansweredContactsCount)
                            <span class="badge bg--danger py-1 px-1"></span>
                        @endif
                    </span>
                    <span class="sidebar-txt">@lang('Contacts')</span>
                </a>
            </li>
        @endcan

        @can('view all subscribers')
            <li class="sidebar-item">
                <a href="{{ route('admin.subscriber.index') }}" class="sidebar-link {{ navigationActive('admin.subscriber.index', 2) }}">
                    <span class="nav-icon"><i class="ti ti-message-plus"></i></span>
                    <span class="sidebar-txt">@lang('Subscribers')</span>
                </a>
            </li>
        @endcan

        @can('view basic and system settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.basic.setting') }}" class="sidebar-link {{ navigationActive('admin.basic.setting', 2) }}">
                    <span class="nav-icon"><i class="ti ti-settings"></i></span>
                    <span class="sidebar-txt">@lang('Basic Settings')</span>
                </a>
            </li>
        @endcan

        @can('view cronjob settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.cronjob.index') }}" class="sidebar-link {{ navigationActive('admin.cronjob.index*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-clock"></i></span>
                    <span class="sidebar-txt">@lang('Cronjob')</span>
                </a>
            </li>
        @endcan

        @can('view referral settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.referral.settings') }}" class="sidebar-link {{ navigationActive('admin.referral.settings', 2) }}">
                    <span class="nav-icon"><i class="ti ti-binary-tree-2"></i></span>
                    <span class="sidebar-txt">@lang('Referral Settings')</span>
                </a>
            </li>
        @endcan

        @canany(['view universal template', 'view email configuration', 'view sms configuration', 'view all templates'])
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.notification*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-mail"></i></span>
                    <span class="sidebar-txt">@lang('Email & SMS')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @can('view universal template')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.notification.universal') }}" class="sidebar-link {{ navigationActive('admin.notification.universal', 1) }}">
                                @lang('Universal Template')
                            </a>
                        </li>
                    @endcan
                    
                    @can('view email configuration')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.notification.email') }}" class="sidebar-link {{ navigationActive('admin.notification.email', 1) }}">
                                @lang('Email Config')
                            </a>
                        </li>
                    @endcan

                    @can('view sms configuration')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.notification.sms') }}" class="sidebar-link {{ navigationActive('admin.notification.sms', 1) }}">
                                @lang('SMS Config')
                            </a>
                        </li>
                    @endcan

                    @can('view all templates')
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.notification.templates') }}" class="sidebar-link {{ navigationActive('admin.notification.templates', 1) }}">
                                @lang('All Templates')
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view all plugins')
            <li class="sidebar-item">
                <a href="{{ route('admin.plugin.setting') }}" class="sidebar-link {{ navigationActive('admin.plugin.setting', 2) }}">
                    <span class="nav-icon"><i class="ti ti-plug"></i></span>
                    <span class="sidebar-txt">@lang('Plugins')</span>
                </a>
            </li>
        @endcan

        @can('view all languages')
            <li class="sidebar-item">
                <a href="{{ route('admin.language.index') }}" class="sidebar-link {{ navigationActive('admin.language*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-language"></i></span>
                    <span class="sidebar-txt">@lang('Language')</span>
                </a>
            </li>
        @endcan

        @can('view seo settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.seo.setting') }}" class="sidebar-link {{ navigationActive('admin.seo.setting', 2) }}">
                    <span class="nav-icon"><i class="ti ti-seo"></i></span>
                    <span class="sidebar-txt">@lang('SEO')</span>
                </a>
            </li>
        @endcan

        @can('view kyc settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.kyc.setting') }}" class="sidebar-link {{ navigationActive('admin.kyc*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-user-scan"></i></span>
                    <span class="sidebar-txt">@lang('KYC')</span>
                </a>
            </li>
        @endcan

        @can('view theme settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.site.themes') }}" class="sidebar-link {{ navigationActive('admin.site.themes', 2) }}">
                    <span class="nav-icon"><i class="ti ti-template"></i></span>
                    <span class="sidebar-txt">@lang('Themes')</span>
                </a>
            </li>
        @endcan

        @can('view home page sections')
            <li class="sidebar-item">
                <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.site.sections*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-layout-grid-add"></i></span>
                    <span class="sidebar-txt">@lang('Site Content')</span>
                </a>
                <ul class="sidebar-dropdown-menu">
                    @php $lastSegment = collect(request()->segments())->last() @endphp

                    @foreach(getPageSections(true) as $key => $section)
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('admin.site.sections', $key) }}" @class(['sidebar-link', 'active' => $lastSegment == $key])>
                                {{ __($section['name']) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endcan

        @can('view cookie settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.cookie.setting') }}" class="sidebar-link {{ navigationActive('admin.cookie.setting', 2) }}">
                    <span class="nav-icon"><i class="ti ti-cookie"></i></span>
                    <span class="sidebar-txt">@lang('GDPR Cookie')</span>
                </a>
            </li>
        @endcan

        @can('view maintenance settings')
            <li class="sidebar-item">
                <a href="{{ route('admin.maintenance.setting') }}" class="sidebar-link {{ navigationActive('admin.maintenance.setting', 2) }}">
                    <span class="nav-icon"><i class="ti ti-tool"></i></span>
                    <span class="sidebar-txt">@lang('Maintenance')</span>
                </a>
            </li>
        @endcan

        <li class="sidebar-item">
            <a href="#cacheClearModal" class="sidebar-link" data-bs-toggle="modal">
                <span class="nav-icon"><i class="ti ti-eraser"></i></span>
                <span class="sidebar-txt">@lang('Cache Clear')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#systemInfoModal" class="sidebar-link" data-bs-toggle="modal">
                <span class="nav-icon"><i class="ti ti-info-square-rounded"></i></span>
                <span class="sidebar-txt">@lang('System Info')</span>
            </a>
        </li>
    </ul>
</div>

<div class="custom--modal modal fade" id="cacheClearModal" tabindex="-1" aria-labelledby="cacheClearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="cacheClearModalLabel">@lang('Flush System Cache')</h2>
                <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form action="{{ route('admin.cache.clear') }}" method="GET">
                <div class="modal-body">
                    <ul class="cache-clear-list">
                        <li>@lang('The cache containing compiled views will be removed')</li>
                        <li>@lang('The cache containing application will be removed')</li>
                        <li>@lang('The cache containing route will be removed')</li>
                        <li>@lang('The cache containing configuration will be removed')</li>
                        <li>@lang('The cache containing system will be removed')</li>
                        <li>@lang('Clearing out the compiled service and package files')</li>
                    </ul>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--sm btn--base">@lang('Clear')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom--modal modal fade" id="systemInfoModal" tabindex="-1" aria-labelledby="systemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="systemInfoModalLabel">@lang('System Information')</h2>
                <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="custom--tab nav nav-tabs flex-nowrap mb-3" role="tablist">
                        <button class="nav-link w-100 active" id="nav-application-tab" data-bs-toggle="tab" data-bs-target="#nav-application" type="button" role="tab" aria-controls="nav-application" aria-selected="true">
                            @lang('Application')
                        </button>
                        <button class="nav-link w-100" id="nav-server-tab" data-bs-toggle="tab" data-bs-target="#nav-server" type="button" role="tab" aria-controls="nav-server" aria-selected="false">
                            @lang('Server')
                        </button>
                    </div>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="nav-application" role="tabpanel" aria-labelledby="nav-application-tab" tabindex="0">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">{{ systemDetails()['name'] }} @lang('Version')</td>
                                    <td>{{ systemDetails()['version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Build Version')</td>
                                    <td>{{ systemDetails()['build_version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Laravel Version')</td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Timezone')</td>
                                    <td>{{ config('app.timezone') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-server" role="tabpanel" aria-labelledby="nav-server-tab" tabindex="0">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">@lang('PHP Version')</td>
                                    <td>{{ phpversion() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Software')</td>
                                    <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server IP Address')</td>
                                    <td>{{ $_SERVER['SERVER_ADDR'] ?? request()->server('LOCAL_ADDR', '127.0.0.1') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Protocol')</td>
                                    <td>{{ $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('HTTP Host')</td>
                                    <td>{{ $_SERVER['HTTP_HOST'] ?? request()->getHost() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Port')</td>
                                    <td>{{ $_SERVER['SERVER_PORT'] ?? request()->getPort() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
