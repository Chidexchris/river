@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Code')</th>
                        <th>@lang('Routing Number')</th>
                        <th>@lang('Swift Code')</th>
                        <th>@lang('Contact Number')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('Map Location')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{ __($branch->name) }}</td>
                            <td>{{ $branch->code }}</td>
                            <td>{{ $branch->routing_number }}</td>
                            <td>{{ $branch->swift_code }}</td>
                            <td>{{ $branch->contact_number ?? '-' }}</td>
                            <td>{{ $branch->email ?? '-' }}</td>
                            <td>{{ $branch->address }}</td>
                            <td>
                                <button type="button" @class(['btn btn--sm btn--base btn-map-location', 'disabled' => is_null($branch->map_location)]) data-name="{{ __($branch->name) }}" data-map_location="{{ $branch->map_location }}">
                                    <i class="ti ti-map"></i> @lang('View')
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Map Location Modal --}}
    <div class="custom--modal modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="locationModalLabel"></h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mapView"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        #mapView iframe {
            width: 100%;
            aspect-ratio: 16/9;
            vertical-align: middle;
            border-radius: 5px;
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.btn-map-location').on('click', function () {
                    let branchName = $(this).data('name')
                    let mapLocation = $(this).data('map_location')
                    let modal = $('#locationModal')

                    modal.find('#locationModalLabel').text(branchName)
                    modal.find('#mapView').html(mapLocation)
                    modal.modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
