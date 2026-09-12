@extends("{$activeTheme}layouts.frontend")

@section('frontend')
    @include("{$activeTheme}sections.faq")
    @include("{$activeTheme}sections.subscribe")
    @include("{$activeTheme}sections.counters")
@endsection
