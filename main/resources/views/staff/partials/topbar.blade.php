<header class="header" id="header">
    <div class="header__container">
        <div class="header__logo">
            <div class="header__logo__big">
                <a href="{{ route('staff.dashboard') }}">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="Logo">
                </a>
            </div>
            <div class="header__logo__small">
                <a href="{{ route('staff.dashboard') }}">
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
                @if(isManager())
                    <form action="{{ route('staff.switch.branch') }}" method="post">
                        @csrf
                        <input type="hidden" id="branchInput" name="branch">
                        <select class="form--control" id="selectBranch">
                            @foreach($staff->branches as $branch)
                                <option value="{{ $branch->id }}" @selected(session('branchId') == $branch->id)>
                                    {{ __($branch->name) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <div class="header__nav__admin dropdown custom--dropdown">
                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ getImage(getFilePath('staffProfile') . '/' . auth('staff')->user()->image, getFileSize('staffProfile'), true) }}" alt="image">
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('staff.profile') }}" class="dropdown-item">
                                <span class="dropdown-icon"><i class="ti ti-user text--info"></i></span> @lang('Profile')
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('staff.logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item w-100">
                                    <span class="dropdown-icon"><i class="ti ti-logout text--danger"></i></span> @lang('Logout')
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('#selectBranch').on('change', function () {
                    $('#branchInput').val($(this).find(':selected').val())
                    $(this).parent().submit()
                })
            })
        })(jQuery)
    </script>
@endpush
