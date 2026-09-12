@extends("{$activeTheme}layouts.frontend")

@section('frontend')
    <div class="contact py-120">
        <div class="container">
            <div class="row gy-5 justify-content-lg-between justify-content-center">
                @if(count($contactElements))
                    @foreach($contactElements as $contact)
                        <div class="col-lg-4 col-sm-6">
                            <div class="contact__card">
                                <div class="contact__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . $contactContent->data_info->background_image, '500x375') }}"></div>
                                <span class="contact__card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . $contact->data_info->image) }}" alt="Icon">
                                </span>
                                <h3 class="contact__card__title">
                                    {{ __($contact->data_info->heading) }}
                                </h3>
                                <p>{{ __($contact->data_info->data) }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="col-lg-5 col-sm-8">
                    <div class="contact__thumb">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . $contactContent->data_info->image, '601x652') }}" alt="image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-10 d-flex align-items-center">
                    <div class="custom--card h-auto">
                        <div class="card-header">
                            <h3 class="title">@lang('We\'re waiting to hear from you')</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" class="row g-3">
                                @csrf
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Your Full Name')</label>
                                    <input type="text" class="form--control" name="name" value="{{ old('name', $user?->fullname) }}" @readonly($user) required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Your Email')</label>
                                    <input type="email" class="form--control" name="email" value="{{ old('email', $user?->email) }}" @readonly($user) required>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Subject')</label>
                                    <input type="text" class="form--control" name="subject" value="{{ old('subject') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Message')</label>
                                    <textarea class="form--control" name="message" rows="10" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn--base">@lang('Send Message')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-120">
                <div class="custom--card" data-aos="fade-up" data-aos-duration="1500">
                    <div class="card-body">
                        <div class="contact__map">
                            <iframe src="https://maps.google.com/maps?hl=en&amp;q={{ $contactContent->data_info->latitude }},%20{{ $contactContent->data_info->longitude }}+({{ $setting->site_name }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" loading="lazy" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
