@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Your Provided Information')</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @if($user->kyc_data)
                            <div class="col-12">
                                <table class="table table-borderless table-light no-shadow">
                                    <tbody>
                                        @foreach($user->kyc_data as $val)
                                            @continue(!$val->value)

                                            <tr>
                                                <td>{{ __($val->name) }}</td>
                                                <td>
                                                    @if($val->type == 'checkbox')
                                                        {{ implode(',', $val->value) }}
                                                    @elseif($val->type == 'file')
                                                        <a href="{{ route('user.file.download') }}?filePath=verify&fileName={{ $val->value }}">
                                                            <i class="ti ti-download"></i> @lang('Download')
                                                        </a>
                                                    @else
                                                        {{ __($val->value) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @include($activeTheme . 'partials.basicNoData')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
