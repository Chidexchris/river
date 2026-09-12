@extends('admin.layouts.master')

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
                        <th>@lang('Status')</th>

                        @canany(['edit branch', 'change branch status'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($branches as $branch)
                        <tr>
                            <td>{{ __($branch->name) }}</td>
                            <td>{{ $branch->code }}</td>
                            <td>{{ $branch->routing_number }}</td>
                            <td>{{ $branch->swift_code }}</td>
                            <td>{{ $branch->contact_number ?? '-' }}</td>
                            <td>{{ $branch->email ?? '-' }}</td>
                            <td>
                                @php echo $branch->status_badge @endphp
                            </td>

                            @canany(['edit branch', 'change branch status'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit branch')
                                                <li>
                                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="dropdown-item">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Branch')
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('change branch status')
                                                @if($branch->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.branches.status', $branch->id) }}" data-question="@lang('Are you sure you want to inactive this branch?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-x text--warning"></i></span> @lang('Inactive Branch')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.branches.status', $branch->id) }}" data-question="@lang('Are you sure you want to active this branch?')">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Active Branch')
                                                        </button>
                                                    </li>
                                                @endif
                                            @endcan
                                        </ul>
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

        @if ($branches->hasPages())
            {{ paginateLinks($branches) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('create branch')
        <a href="{{ route('admin.branches.create') }}" class="btn btn--sm btn--base d-flex align-items-center gap-1">
            <i class="ti ti-circle-plus transform-0"></i> @lang('Create New')
        </a>
    @endcan
@endpush
