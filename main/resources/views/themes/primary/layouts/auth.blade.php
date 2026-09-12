@extends("{$activeTheme}layouts.app")

@section('content')
    <header class="header-2">
        <div class="header-2__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/user_panel/' . $userPanelContent?->data_info->background_image, '1920x1080') }}"></div>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img class="riverwind-logo riverwind-logo-light" src="{{ asset('assets/themes/primary/images/riverwind-logo.svg') }}" alt="RiverWind Bank">
                <img class="riverwind-logo riverwind-logo-dark" src="{{ asset('assets/themes/primary/images/riverwind-logo-dark.svg') }}" alt="RiverWind Bank">
            </a>
            <button type="button" class="sidebar-toggler">
                <span id="hiddenNav"><i class="ti ti-menu-2 fz-0"></i></span>
            </button>
        </div>
        <div class="header-menu">
            <div class="account-info">
                <span class="page-name">{{ __($pageTitle) }}</span>
                <span class="account-number">{{ __('A/C No.') . ' ' . $user->account_number }}</span>
            </div>

            <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="ti ti-moon"></i>
            </button>

            <div class="header-2__user d-lg-flex align-items-center gap-2 d-none">
                <div class="header-2__user__txt">
                    <span class="header-2__user__username">{{ '@' . $user->username }}</span>
                    <span class="header-2__user__balance">@lang('Balance'): <strong>{{ showAmount($user->balance) . ' ' . $setting->site_cur }}</strong></span>
                </div>
                <div class="header-2__user__img">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                </div>
            </div>
        </div>
    </header>
    <div class="dashboard">
        <div class="sidebar-overlay-2"></div>
        <div class="main-sidebar">
            <div class="main-sidebar__user-wrap d-lg-none">
                <div class="main-sidebar__user">
                    <div class="main-sidebar__user__img">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                    </div>
                    <div class="main-sidebar__user__txt">
                        <span class="main-sidebar__user__username">{{ '@' . $user->username }}</span>
                        <span class="main-sidebar__user__balance">@lang('Balance'): <strong>{{ showAmount($user->balance) . ' ' . $setting->site_cur }}</strong></span>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('user.home') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-layout-dashboard"></i></span>
                        <span class="sidebar-txt">@lang('Dashboard')</span>
                    </a>
                </li>

                @if($setting->dps)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-moneybag"></i></span>
                            <span class="sidebar-txt">@lang('DPS')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.dps.plans') }}" class="sidebar-link">@lang('DPS Plans')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.dps.list') }}" class="sidebar-link">@lang('My DPS List')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($setting->fds)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-cash-register"></i></span>
                            <span class="sidebar-txt">@lang('FDS')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.fds.plans') }}" class="sidebar-link">@lang('FDS Plans')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.fds.list') }}" class="sidebar-link">@lang('My FDS List')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($setting->loan)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-cash"></i></span>
                            <span class="sidebar-txt">@lang('Loan')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.loan.plans') }}" class="sidebar-link">@lang('Loan Plans')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.loan.list') }}" class="sidebar-link">@lang('My Loan List')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($setting->internal_bank_transfer || $setting->external_bank_transfer || $setting->wire_transfer)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-arrows-right-left"></i></span>
                            <span class="sidebar-txt">@lang('Money Transfer')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            @if($setting->internal_bank_transfer)
                                <li class="sidebar-dropdown-item">
                                    <a href="{{ route('user.money.transfer.within.bank') }}" class="sidebar-link">{{ trans('Within RiverWind Bank') }}</a>
                                </li>
                            @endif

                            @if($setting->external_bank_transfer)
                                <li class="sidebar-dropdown-item">
                                    <a href="{{ route('user.money.transfer.other.bank') }}" class="sidebar-link">@lang('Other Bank')</a>
                                </li>
                            @endif

                            @if($setting->wire_transfer)
                                <li class="sidebar-dropdown-item">
                                    <a href="{{ route('user.money.transfer.wire.transfer') }}" class="sidebar-link">@lang('Wire Transfer')</a>
                                </li>
                            @endif

                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.money.transfer.history') }}" class="sidebar-link">@lang('Transfer History')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($setting->deposit)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-wallet"></i></span>
                            <span class="sidebar-txt">@lang('Deposit')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.deposit') }}" class="sidebar-link">@lang('Deposit Money')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.deposit.history') }}" class="sidebar-link">@lang('Deposit History')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($setting->withdraw)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-cash-banknote"></i></span>
                            <span class="sidebar-txt">@lang('Withdraw')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.withdraw') }}" class="sidebar-link">@lang('Withdraw Money')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.withdraw.history') }}" class="sidebar-link">@lang('Withdraw History')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="sidebar-item">
                    <a href="{{ route('user.transactions') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-transform"></i></span>
                        <span class="sidebar-txt">@lang('Transactions')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('user.account.statement') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-file-invoice"></i></span>
                        <span class="sidebar-txt">@lang('Account Statement')</span>
                    </a>
                </li>

                @if($setting->referral_system)
                    <li class="sidebar-item">
                        <a href="{{ route('user.referred.users') }}" class="sidebar-link">
                            <span class="nav-icon"><i class="ti ti-binary-tree-2"></i></span>
                            <span class="sidebar-txt">@lang('Referral')</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-item">
                    <a role="button" class="sidebar-link has-sub">
                        <span class="nav-icon"><i class="ti ti-settings"></i></span>
                        <span class="sidebar-txt">@lang('Settings')</span>
                    </a>
                    <ul class="sidebar-dropdown-menu">
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.profile') }}" class="sidebar-link">@lang('Profile Settings')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.change.password') }}" class="sidebar-link">@lang('Change Password')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.twofactor.form') }}" class="sidebar-link">@lang('2FA Settings')</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <a href="{{ route('user.logout') }}" class="logout-btn btn btn--sm btn--base">
                <i class="ti ti-logout"></i> @lang('Log Out')
            </a>
        </div>
        <div class="main-content">
            {{-- background image removed — dark mode CSS controls the content area background --}}
            
            @yield('auth')
        </div>
    </div>
@endsection
