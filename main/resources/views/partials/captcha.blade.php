@php $googleCaptcha = loadReCaptcha() @endphp

@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif

@pushIf($googleCaptcha, 'page-script')
    <script>
        (function ($) {
            "use strict";

            $('.verify-gcaptcha').on('submit', function () {
                let response = grecaptcha.getResponse();

                if (response.length === 0) {
                    document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang('Captcha field is required.')</span>';

                    return false;
                }

                return true;
            });
        })(jQuery);
    </script>
@endPushIf
