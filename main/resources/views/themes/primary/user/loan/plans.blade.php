@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="container">
        <div class="row g-4 justify-content-center">
            @forelse($loanPlans as $loanPlan)
                @include("{$activeTheme}partials.loanPlan")
            @empty
                @include("{$activeTheme}partials.basicNoData")
            @endforelse
        </div>
    </div>
@endsection
