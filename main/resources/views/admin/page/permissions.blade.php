@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="" method="post">
            @csrf
            @foreach($permissions as $module => $modulePermissions)
                <div @class(['custom--card', 'mt-4' => !$loop->first])>
                    <div class="card-header">
                        <h6 class="card-title">{{ __($module) }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach($modulePermissions as $permission)
                                <div class="col-lg-4">
                                    <label class="form--label">{{ $permission['route_name'] }}<span class="badge @if($permission['method'] == 'GET') badge--success @else badge--warning @endif ms-2">{{ $permission['method'] }}</span></label>
                                    <div class="input--group">
                                        <span class="input-group-text">@lang('Name')</span>
                                        <input type="text" class="form--control" name="permissions[{{ $module }}][{{ $permission['permission'] }}]" value="{{ $permission['permission'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($loop->last)
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </form>
    </div>
@endsection
