@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Please provide the following information')</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf

                        <x-phinixForm identifier="act" identifierValue="kyc" />

                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
