@php $subscribeContent = getSiteData('subscribe.content', true) @endphp

<section class="subscribe py-120 section-bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/subscribe/' . $subscribeContent->data_info->background_image, '1920x700') }}">
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-8">
                <div class="section-heading section-heading-light text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($subscribeContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($subscribeContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($subscribeContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($subscribeContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="row g-3 justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="subscribe__form" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                    <div class="input--group">
                        <input type="email" class="form--control" id="subscriberEmail" placeholder="@lang('Enter Your Email Address')">
                        <button type="button" class="btn btn--base px-lg-5" id="subscribeBtn" data-subscriber_url="{{ route('subscriber.store') }}">
                            @lang('Subscribe') <i class="ti ti-arrow-up-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $('#subscribeBtn').on('click', subscribe);
            $('#subscriberEmail').on('keypress', function (e) {
                if (e.which === 13) {
                    subscribe();
                }
            });

            function subscribe() {
                let email = $('#subscriberEmail').val();
                let url = $('#subscribeBtn').data('subscriber_url');

                if (email) {
                    let data = {
                        email,
                        _token: "{{ csrf_token() }}",
                    };

                    $.post(url, data, function (response) {
                        if (response.success) {
                            $('#subscriberEmail').val('');
                            showToasts('success', response.success);
                        } else {
                            showToasts('error', response.error);
                        }
                    });
                } else {
                    showToasts('warning', 'Please enter a valid email address');
                }
            }
        })(jQuery)
    </script>
@endpush
