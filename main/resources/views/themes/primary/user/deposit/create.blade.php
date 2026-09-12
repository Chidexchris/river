@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row g-4 justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Create Deposit Plan')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ url('user/deposit-plan/store') }}" method="post" class="row g-4">
                        @csrf
                        <div class="col-12">
                            <label class="form--label required">@lang('Plan Name')</label>
                            <input type="text" name="plan_name" class="form--control" required>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Daily Amount')</label>
                            <input type="number" step="any" name="daily_amount" class="form--control" required>
                        </div>
                        <div class="col-12">
                            <label class="form--label">@lang('Currency')</label>
                            <input type="text" name="currency" readonly class="form--control" value="NGN" required>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Duration (days)')</label>
                            <input type="number" name="duration_days" class="form--control" required>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Start Date')</label>
                            <input type="date" name="start_date" class="form--control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Save Plan')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Show existing deposit plans --}}
    @if($depositPlans->count() > 0)
        <div class="row g-4 justify-content-center mt-5">
            <div class="col-lg-8">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Your Deposit Plans')</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Plan Name')</th>
                                    <th>@lang('Daily Amount')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Start Date')</th>
                                    <th>@lang('Currency')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($depositPlans as $plan)
                                    <tr>
                                        <td>{{ $plan->plan_name }}</td>
                                        <td>{{ $plan->daily_amount }}</td>
                                        <td>{{ $plan->duration_days }} days</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->start_date)->format('d M, Y') }}</td>
                                        <td>{{ $plan->currency }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/universal/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush
