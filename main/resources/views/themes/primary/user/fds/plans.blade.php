@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="container">
        <div class="row g-4 justify-content-center">
            @forelse($fdsPlans as $fdsPlan)
                @include("{$activeTheme}partials.fdsPlan")
            @empty
                @include("{$activeTheme}partials.basicNoData")
            @endforelse
        </div>
    </div>
@endsection
