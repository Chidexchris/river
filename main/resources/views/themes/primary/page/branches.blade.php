@extends("{$activeTheme}layouts.frontend")

@section('frontend')
    <div class="branches py-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table no-shadow table--striped table-borderless table--responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('Name') | @lang('Code')</th>
                                    <th>@lang('Routing Number')</th>
                                    <th>@lang('Contact Number')</th>
                                    <th>@lang('Email Address')</th>
                                    <th>@lang('Address')</th>
                                    <th>@lang('Map')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td>
                                            <span>
                                                <span class="d-block">{{ __($branch->name) }}</span>
                                                <span class="d-block text--base small">{{ $branch->code }}</span>
                                            </span>
                                        </td>
                                        <td>{{ $branch->routing_number }}</td>
                                        <td>{{ $branch->contact_number ?? '-' }}</td>
                                        <td>{{ $branch->email ?? '-' }}</td>
                                        <td>{{ $branch->address }}</td>
                                        <td>
                                            <button type="button" @class(['btn btn--base btn--icon btn-map-location', 'disabled' => is_null($branch->map_location)]) data-name="{{ __($branch->name) }}" data-map_location="{{ $branch->map_location }}">
                                                <i class="ti ti-map transform-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @include('partials.noData')
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($branches->hasPages())
                        {{ $branches->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('user-panel-modal')
    <div class="custom--modal modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="locationModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mapView"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

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
