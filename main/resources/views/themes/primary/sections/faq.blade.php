@php
    $faqContent  = getSiteData('faq.content', true);
    $faqElements = getSiteData('faq.element', false, null, true)
@endphp

<div class="faq py-120" id="faq">
    <div class="container">
        @if(request()->routeIs('home'))
            <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                <div class="col-md-8">
                    <div class="section-heading text-center">
                        <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($faqContent->data_info->section_second_subtitle) }}</span>
                        <p class="section-heading__subtitle">{{ __($faqContent->data_info->section_first_subtitle) }}</p>
                        <h2 class="section-heading__title">{{ __($faqContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($faqContent->data_info->highlighted_part) }}</span></h2>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4 align-items-center justify-content-lg-between justify-content-center">
            <div class="col-lg-6 col-md-10 order-md-1 order-2">
                @if(count($faqElements))
                    <div class="accordion custom--accordion" id="faqAccordion">
                        @foreach($faqElements as $faq)
                            <div @class(['accordion-item', 'opened' => $loop->first]) data-aos="fade-right" data-aos-duration="1000" data-aos-offset="100">
                                <h2 class="accordion-header">
                                    <button type="button" @class(['accordion-button', 'collapsed' => !$loop->first]) data-bs-toggle="collapse" data-bs-target="{{ '#collapse' . $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ 'collapse' . $loop->iteration }}">
                                        {{ __($faq->data_info->question) }}
                                    </button>
                                </h2>
                                <div id="{{ 'collapse' . $loop->iteration }}" @class(['accordion-collapse collapse', 'show' => $loop->first]) data-bs-parent="#faqAccordion">
                                    <div class="accordion-body styled-list-parent">
                                        @php echo $faq->data_info->answer @endphp
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @include($activeTheme . 'partials.basicNoData')
                @endif
            </div>
            <div class="col-lg-5 col-sm-8 col-xsm-8 col-10 order-md-2 order-1">
                <div class="faq__img" data-aos="fade-left" data-aos-duration="1000" data-aos-offset="100">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/faq/' . $faqContent->data_info->image, '601x563') }}" alt="faq">
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-style')
    <style>
        .styled-list-parent ul {
            padding-left: 2rem;
            list-style: disc;
        }

        .styled-list-parent ol {
            padding-left: 2rem;
            list-style: decimal;
        }
    </style>
@endpush
