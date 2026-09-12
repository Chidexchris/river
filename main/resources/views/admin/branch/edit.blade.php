@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.branches.update', $branch) }}" method="post">
            @csrf
            <div class="custom--card">
                <div class="card-body">
                    <div class="row g-lg-4 g-3">
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Name')</label>
                            <input type="text" class="form--control" name="name" value="{{ old('name', $branch->name) }}" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Branch Code')</label>
                            <input type="text" class="form--control" name="code" value="{{ old('code', $branch->code) }}" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Routing Number')</label>
                            <input type="number" min="0" class="form--control" name="routing_number" value="{{ old('routing_number', $branch->routing_number) }}" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label required">@lang('Swift Code')</label>
                            <input type="text" class="form--control" name="swift_code" value="{{ old('swift_code', $branch->swift_code) }}" required>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label">@lang('Contact Number')</label>
                            <input type="text" class="form--control" name="contact_number" value="{{ old('contact_number', $branch->contact_number) }}">
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label class="form--label">@lang('Email Address')</label>
                            <input type="email" class="form--control" name="email" value="{{ old('email', $branch->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form--label required">@lang('Address')</label>
                            <textarea class="form--control" name="address" required>{{ old('address', $branch->address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form--label">@lang('Map Location')</label>
                            <textarea class="form--control" name="map_location">{{ old('map_location', $branch->map_location) }}</textarea>
                        </div>

                        @if($branch->map_location)
                            <div class="col-12">
                                <div class="contact__map">
                                    @php echo $branch->map_location @endphp
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @can('update branch')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Update')</button>
                        </div>
                    </div>
                @endcan
            </div>
        </form>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.branches.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('page-style')
    <style>
        .contact__map iframe {
            width: 100%;
            aspect-ratio: 16/5;
            vertical-align: middle;
            border-radius: 5px;
        }
    </style>
@endpush
