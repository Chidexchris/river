@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="container">
        <div class="row g-4 justify-content-center">
            @forelse($dpsPlans as $dpsPlan)
                @include("{$activeTheme}partials.dpsPlan")
            @empty
                @include("{$activeTheme}partials.basicNoData")
            @endforelse
        </div>
    </div>
@endsection
