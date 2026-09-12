@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row gy-4">
        @if($user->referrer)
            <div class="col-12">
                <div class="alert alert--base" role="alert">
                    <h5 class="mb-1 text-center">
                        @lang('You have been referred by') <span class="alert__title d-inline">{{ $user->referrer->fullname }}</span>
                    </h5>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="custom--card">
                <div class="card-body">
                    @if(count($user->referrals) > 0)
                        <div class="treeview-container">
                            <ul class="treeview">
                                <li class="items-expanded">
                                    {{ $user->fullname }}
                                    @include("{$activeTheme}partials.referralNode", ['user' => $user, 'isFirst' => true, 'level' => 1])
                                </li>
                            </ul>
                        </div>
                    @else
                        <p class="text-center">@lang('No users have been referred yet!')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/treeView.css") }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset("{$activeThemeTrue}js/treeView.js") }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.treeview').treeView()
            })
        })(jQuery)
    </script>
@endpush
