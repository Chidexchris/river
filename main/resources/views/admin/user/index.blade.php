@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('Email') | @lang('Phone')</th>
                        <th>@lang('Country')</th>
                        <th>@lang('Joined')</th>
                        <th>@lang('Balance')</th>

                        @canany(['view user details', 'approve kyc application', 'reject kyc application'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="table-card-with-image">
                                    <div class="table-card-with-image__img">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                                    </div>
                                    <div class="table-card-with-image__content">
                                        <p class="fw-semibold">{{ $user->fullname }}</p>
                                        <p class="fw-semibold">
                                            <a href="{{ route('admin.user.details', $user->id) }}">
                                                <small>@</small>{{ $user->username }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ $user->email }}</p>
                                    <p>{{ $user->mobile }}</p>
                                </div>
                            </td>
                            <td><p class="fw-bold" title="{{ __($user->country_name) }}">{{ $user->country_code }}</p></td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($user->created_at) }}</p>
                                    <p>{{ diffForHumans($user->created_at) }}</p>
                                </div>
                            </td>
                            <td><span class="fw-bold">{{ $setting->cur_sym }}{{ showAmount($user->balance) }}</span></td>

                            @canany(['view user details', 'approve kyc application', 'reject kyc application'])
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('view user details')
                                            <a href="{{ route('admin.user.details', $user->id) }}" class="btn btn--sm btn-outline--base">
                                                <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                            </a>
                                        @endcan

                                        @canany(['approve kyc application', 'reject kyc application'])
                                            @if (request()->routeIs('admin.user.kyc.pending'))
                                                <div class="custom--dropdown">
                                                    <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="#kycDetails" class="dropdown-item detailBtn" data-bs-toggle="offcanvas" data-kyc_data="{{ json_encode($user->kyc_data) }}">
                                                                <span class="dropdown-icon"><i class="ti ti-info-hexagon text--info"></i></span> @lang('KYC Details')
                                                            </a>
                                                        </li>

                                                        @can('approve kyc application')
                                                            <li>
                                                                <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you confirm the approval of this documents')?" data-action="{{ route('admin.user.kyc.approve', $user->id) }}">
                                                                    <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Approve')
                                                                </button>
                                                            </li>
                                                        @endcan

                                                        @can('reject kyc application')
                                                            <li>
                                                                <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you confirm the cancellation of this documents')?" data-action="{{ route('admin.user.kyc.cancel', $user->id) }}">
                                                                    <span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Cancel')
                                                                </button>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            @endif
                                        @endcanany
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            {{ paginateLinks($users) }}
        @endif
    </div>

    @if (request()->routeIs('admin.user.kyc.pending'))
        <div class="col-12">
            <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="kycDetails" aria-labelledby="kycDetailsLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="kycDetailsLabel">@lang('KYC Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <table class="table table-borderless mb-3">
                        <tbody class="kycData"></tbody>
                    </table>
                    <div class="d-flex justify-content-center gap-2 action-div"></div>
                </div>
            </div>
        </div>

        <x-decisionModal />
    @endif
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Username / Email" dateSearch="yes" />
@endpush

@if (request()->routeIs('admin.user.kyc.pending'))
    @push('page-script')
        <script>
            (function ($) {
                "use strict";

                $('.detailBtn').on('click', function () {
                    let kycData  = $(this).data('kyc_data');
                    let infoHtml = ``;

                    if (kycData) {
                        let fileDownloadUrl = '{{ route("admin.file.download", ["filePath" => "verify"]) }}';

                        kycData.forEach(element => {
                            if (!element.value) { return; }

                            if(element.type !== 'file') {
                                infoHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>${element.value}</td>
                                            </tr>`;
                            } else {
                                infoHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>
                                                    <a href="${fileDownloadUrl}&fileName=${element.value}" class="btn btn--sm btn-outline--secondary">
                                                        <i class="ti ti-download"></i> @lang('Download')
                                                    </a>
                                                </td>
                                            </tr>`;
                            }
                        });

                        infoHtml += `<tr>
                                        <td class="fw-bold">@lang('Status')</td>
                                        <td><span class="badge badge--warning">@lang('Pending')</span></td>
                                    </tr>`;
                    } else {
                        infoHtml += `<tr>
                                        <td class="fw-bold">{{ __($emptyMessage) }}</td>
                                    </tr>`;
                    }

                    $('.kycData').html(infoHtml);
                });
            })(jQuery);
        </script>
    @endpush
@endif
