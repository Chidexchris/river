@extends("{$activeTheme}layouts.auth")

@section('auth')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Withdraw via') {{ __($withdraw->method->name) }}</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        <div class="col-12 text-center">
                            <p class="fw-bold text--muted">
                                @lang('You have requested a withdrawal of') <span class="text--base">{{ showAmount($withdraw->amount) . ' ' . __($setting->site_cur) }}</span>.<br> @lang('You will get') <span class="text--base">{{ showAmount($withdraw->final_amount) . ' ' . $withdraw->currency }}</span>.
                            </p>
                            <h5 class="mt-3 mb-0">@lang('Please follow the instruction below')</h5>
                        </div>
                        <div class="col-12">
                            @php echo $withdraw->method->guideline @endphp
                        </div>

                        <x-phinix-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />

                        @if($user->ts)
                            <div class="col-12">
                                <label class="form--label required">@lang('Google Authenticator Code')</label>
                                <input type="text" class="form--control" name="authenticator_code" required>
                            </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
