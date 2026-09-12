<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>RiverWind Bank | {{ __($pageTitle) }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('assets/themes/primary/images/riverwind-favicon.svg') }}">

        <script>
            // Theme initialization to prevent flashing
            const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', savedTheme);
        </script>

        @include('partials.seo')

        <link rel="stylesheet" href="{{ asset('assets/universal/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/tabler.css') }}">
        <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/aos.css") }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/nice-select.css') }}">

        @stack('page-style-lib')

        <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/main.css") }}">
        <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/color.php?color1=$setting->primary_color&color2=$setting->secondary_color") }}">
        <link rel="stylesheet" href="{{ asset("{$activeThemeTrue}css/premium-bank.css") }}">

        @stack('page-style')
    </head>
    <body>
        <div class="preloader">
            <div class="loader">
                <div class="riverwind-preloader-mark">
                    <img src="{{ asset('assets/themes/primary/images/riverwind-favicon.svg') }}" alt="">
                </div>
                <span>RiverWind Bank</span>
            </div>
        </div>

        <div class="body-overlay"></div>

        <a class="scroll-top">
            <i class="ti ti-chevrons-up"></i>
        </a>

        @yield('content')

        @stack('user-panel-modal')

        @include('partials.user-chat')

        <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>
        <script src="{{ asset("{$activeThemeTrue}js/viewport.jquery.js") }}"></script>
        <script src="{{ asset('assets/universal/js/nice-select.min.js') }}"></script>
        <script src="{{ asset("{$activeThemeTrue}js/aos.js") }}"></script>

        @stack('page-script-lib')

        <script src="{{ asset("{$activeThemeTrue}js/main.js") }}"></script>

        @include('partials.plugins')
        @include('partials.toasts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Sync all .theme-toggle buttons (dashboard header, landing mobile + desktop nav)
                const allToggles = document.querySelectorAll('.theme-toggle');

                function applyThemeIcons(theme) {
                    allToggles.forEach(btn => {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = theme === 'light' ? 'ti ti-sun' : 'ti ti-moon';
                        }
                    });
                }

                // Set initial icons based on current theme
                applyThemeIcons(document.documentElement.getAttribute('data-theme') || 'dark');

                allToggles.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const current = document.documentElement.getAttribute('data-theme');
                        const next = current === 'dark' ? 'light' : 'dark';
                        document.documentElement.setAttribute('data-theme', next);
                        localStorage.setItem('theme', next);
                        applyThemeIcons(next);
                    });
                });
                // NOTE: Sidebar toggle is handled entirely by main.js (.has-sub, .sidebar-overlay-2)
            });
        </script>

        @stack('page-script')
    </body>
</html>
