@extends("{$activeTheme}layouts.frontend")

@section('frontend')
    <div class="policy py-120">
        <div class="container">
            <div class="row gy-5 justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="card custom--card" data-aos="fade-up" data-aos-duration="1500">
                        <div class="card-header">
                            <h3 class="title">@lang('Read Our') {{ __($pageTitle) }}</h3>
                        </div>
                        <div class="card-body policy--details styled-list-parent">
                            @php echo $maintenance->data_info->details @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .policy--details h1, h2, h3, h4, h5, h6 {
            margin-bottom: .3rem;
        }

        .policy--details.styled-list-parent ul {
            padding-left: 2rem;
            list-style: disc;
        }

        .policy--details.styled-list-parent ol {
            padding-left: 2rem;
            list-style: decimal;
        }
    </style>
@endpush
