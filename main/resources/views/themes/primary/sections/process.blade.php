@php
    $processContent  = getSiteData('process.content', true);
    $processElements = getSiteData('process.element', false, null, true)
@endphp

<section class="process py-120 section-bg">
    <div class="process__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/process/' . $processContent->data_info->background_image, '1920x1080') }}"></div>
    <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
            <div class="col-md-7">
                <div class="section-heading text-center">
                    <span class="section-heading__vector" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">{{ __($processContent->data_info->section_second_subtitle) }}</span>
                    <p class="section-heading__subtitle">{{ __($processContent->data_info->section_first_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __($processContent->data_info->section_title) }} <span class="styled-title" data-mask-image="{{ asset($activeThemeTrue . 'images/title-vector.png') }}">{{ __($processContent->data_info->highlighted_part) }}</span></h2>
                </div>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-12 process__cards" data-divider-text="{{ __($processContent->data_info->section_second_subtitle) }}">
                @forelse($processElements as $process)
                    <div class="process__card" data-aos="{{ $loop->odd ? 'fade-left' : 'fade-right' }}" data-aos-duration="1000" data-aos-offset="100">
                        <div class="process__card__index">{{ $loop->iteration }}</div>
                        <h3 class="process__card__title">{{ $process->data_info->title }}</h3>
                        <p class="process__card__desc">{{ __($process->data_info->short_description) }}</p>
                    </div>
                @empty
                    <div class="no-data-found" data-aos="fade-up" data-aos-duration="1000" data-aos-offset="100">
                        <span>{{ __($emptyMessage) }}</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
