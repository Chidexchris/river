<header class="header" id="header">
    <div class="header__container">
        <div class="header__logo">
            <div class="header__logo__big">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="Logo">
                </a>
            </div>
            <div class="header__logo__small">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/favicon.png') }}" alt="Logo">
                </a>
            </div>
        </div>
        <div class="header__nav">
            <div class="header__nav__left">
                <button class="header__nav__btn sidebar-toggler">
                    <i class="ti ti-menu-2"></i>
                </button>
                <form class="header__search d-md-flex d-none">
                    <span class="header__search__icon">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="search" class="header__search__input" placeholder="@lang('Search')..." id="searchInput" autocomplete="off">
                    <ul class="search-list d-none"></ul>
                </form>
            </div>
            <div class="header__nav__right">
                <a href="{{ route('home') }}" target="_blank" class="header__nav__btn" title="@lang('Visit Website')">
                    <i class="ti ti-world"></i>
                </a>

                @canany(['view pending deposits', 'view pending withdrawals', 'view kyc pending users', 'view basic and system settings'])
                    <div class="dropdown custom--dropdown">
                        <button type="button" class="header__nav__btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-category-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shortcut-dropdown">
                            <li class="dropdown-title">@lang('Shortcuts')</li>
                            <li class="scroll">
                                <div class="shortcut-dropdown__item">
                                    @can('view kyc pending users')
                                        <a href="{{ route('admin.user.kyc.pending') }}" class="shortcut-dropdown__link" title="@lang('Pending KYC')">
                                            <i class="ti ti-user-check"></i>
                                        </a>
                                    @endcan

                                    @can('view pending deposits')
                                        <a href="{{ route('admin.deposits.pending') }}" class="shortcut-dropdown__link" title="@lang('Pending Deposits')">
                                            <i class="ti ti-wallet"></i>
                                        </a>
                                    @endcan

                                    @can('view pending withdrawals')
                                        <a href="{{ route('admin.withdrawals.pending') }}" class="shortcut-dropdown__link" title="@lang('Pending Withdrawals')">
                                            <i class="ti ti-building-bank"></i>
                                        </a>
                                    @endcan

                                    @can('view pending loans')
                                        <a href="{{ route('admin.loan.pending') }}" class="shortcut-dropdown__link" title="@lang('Pending Loans')">
                                            <i class="ti ti-cash"></i>
                                        </a>
                                    @endcan

                                    @can('view pending money transfers')
                                        <a href="{{ route('admin.money.transfers.pending') }}" class="shortcut-dropdown__link" title="@lang('Pending Money Transfers')">
                                            <i class="ti ti-arrows-right-left"></i>
                                        </a>
                                    @endcan

                                    @can('view basic and system settings')
                                        <a href="{{ route('admin.basic.setting') }}" class="shortcut-dropdown__link" title="@lang('Basic Settings')">
                                            <i class="ti ti-settings-cog"></i>
                                        </a>
                                    @endcan
                                </div>
                            </li>
                        </ul>
                    </div>
                @endcanany

                <div class="dropdown custom--dropdown">
                    <button type="button" class="header__nav__btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-bell"></i><span class="badge">{{ $adminNotificationCount > 9 ? '9+' : $adminNotificationCount }}</span>
                    </button>
                    <ul class="dropdown-menu notification-dropdown">
                        <li class="dropdown-item scroll rounded-0">
                            @forelse ($adminNotifications as $notification)
                                <a href="{{ route('admin.system.notification.read', $notification->id) }}">
                                    <span class="notification-dropdown__title">{{ __($notification->title) }}</span>
                                    <span class="notification-dropdown__time">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <span class="notification-dropdown__title">@lang('No notifications left to read')</span>
                            @endforelse
                        </li>

                        @can('view all notifications')
                            <li class="dropdown-btn">
                                <a href="{{ route('admin.system.notification.all') }}" class="btn btn--base w-100">
                                    @lang('View All Notifications')
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
                <div class="header__nav__admin dropdown custom--dropdown">
                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ getImage(getFilePath('adminProfile') . '/' . auth('admin')->user()->image, getFileSize('adminProfile'), true) }}" alt="image">
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <span class="dropdown-icon"><i class="ti ti-user text--info"></i></span> @lang('Profile')
                            </a>
                        </li>

                        @can('view basic and system settings')
                            <li>
                                <a href="{{ route('admin.basic.setting') }}" class="dropdown-item">
                                    <span class="dropdown-icon"><i class="ti ti-settings-cog text--base"></i></span> @lang('Settings')
                                </a>
                            </li>
                        @endcan

                        <li>
                            <a href="{{ route('admin.logout') }}" class="dropdown-item">
                                <span class="dropdown-icon"><i class="ti ti-logout text--danger"></i></span> @lang('Logout')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
