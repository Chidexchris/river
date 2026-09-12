@php
    if (isset($seoContents) && count($seoContents)) {
        $seoContents     = (object) $seoContents;
        $socialImageSize = explode('x', $seoContents->image_size);
    } else if ($seo) {
        $seoContents        = $seo;
        $socialImageSize    = explode('x', getFileSize('seo'));
        $seoContents->image = getImage(getFilePath('seo') . '/' . $seo->image);
    } else {
        $seoContents = null;
    }
@endphp

<meta name="title" content="{{ $setting->siteName(__($pageTitle)) }}">

@if ($seoContents)
    <meta name="description" content="{{ $seoContents->description }}">
    <meta name="keywords" content="{{ implode(',', $seoContents->keywords) }}">
    <link rel="icon" href="{{ asset('assets/themes/primary/images/riverwind-favicon.svg') }}" type="image/svg+xml">

    {{-- Apple Stuff --}}
    <link rel="apple-touch-icon" href="{{ asset('assets/themes/primary/images/riverwind-favicon.svg') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->siteName($pageTitle) }}">

    {{-- Google / Search Engine Tags --}}
    <meta itemprop="name" content="{{ $setting->siteName($pageTitle) }}">
    <meta itemprop="description" content="{{ $seoContents->description }}">
    <meta itemprop="image" content="{{ $seoContents->image }}">

    {{-- Facebook Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seo->social_title }}">
    <meta property="og:description" content="{{ $seo->social_description }}">
    <meta property="og:image" content="{{ $seoContents->image }}" />
    <meta property="og:image:type" content="{{ pathinfo($seoContents->image)['extension'] }}" />
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}" />
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}" />
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
@endif
