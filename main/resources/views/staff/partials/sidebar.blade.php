<div class="main-sidebar">
    <form class="header__search d-md-none">
        <span class="header__search__icon"><i class="ti ti-search"></i></span>
        <input type="search" class="header__search__input" placeholder="@lang('Search')..." id="searchInput" autocomplete="off">
        <ul class="search-list d-none"></ul>
    </form>
    <ul class="sidebar-menu scroll">
        <li class="sidebar-item">
            <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ navigationActive('staff.dashboard', 2) }}">
                <span class="nav-icon"><i class="ti ti-dashboard"></i></span>
                <span class="sidebar-txt">@lang('Dashboard')</span>
            </a>
        </li>

        @if(isManager())
            <li class="sidebar-item">
                <a href="{{ route('staff.branches') }}" class="sidebar-link {{ navigationActive('staff.branches', 2) }}">
                    <span class="nav-icon"><i class="ti ti-sitemap"></i></span>
                    <span class="sidebar-txt">@lang('Branches')</span>
                </a>
            </li>
        @endif

        <li class="sidebar-item">
            <a href="{{ route('staff.accounts.index') }}" class="sidebar-link {{ navigationActive('staff.accounts*', 2) }}">
                <span class="nav-icon"><i class="ti ti-user-circle"></i></span>
                <span class="sidebar-txt">@lang('Accounts')</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('staff.deposits.index') }}" class="sidebar-link {{ navigationActive('staff.deposits*', 2) }}">
                <span class="nav-icon"><i class="ti ti-wallet"></i></span>
                <span class="sidebar-txt">@lang('Deposits')</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('staff.withdrawals.index') }}" class="sidebar-link {{ navigationActive('staff.withdrawals*', 2) }}">
                <span class="nav-icon"><i class="ti ti-building-bank"></i></span>
                <span class="sidebar-txt">@lang('Withdrawals')</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('staff.transactions') }}" class="sidebar-link {{ navigationActive('staff.transactions', 2) }}">
                <span class="nav-icon"><i class="ti ti-arrows-right-left"></i></span>
                <span class="sidebar-txt">@lang('Transactions')</span>
            </a>
        </li>
    </ul>
</div>
