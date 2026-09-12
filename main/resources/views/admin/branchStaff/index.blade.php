@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Username')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Contact Number')</th>
                        <th>@lang('Designation')</th>
                        <th>@lang('Branches')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit branch staff', 'update branch staff', 'change staff status', 'login as staff'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffs as $staff)
                        <tr>
                            <td>{{ __($staff->name) }}</td>
                            <td>{{ __($staff->username) }}</td>
                            <td>{{ $staff->email_address }}</td>
                            <td>{{ $staff->contact_number }}</td>
                            <td>{{ $staff->designation == ManageStatus::BRANCH_MANAGER ? trans('Manager') : trans('Account Officer') }}</td>
                            <td>
                                @php $branches = $staff->branches->pluck('name')->map(fn($name) => __($name)) @endphp

                                {{ $branches->slice(0, 3)->implode(', ') }}{{ $branches->count() > 3 ? '...' : '' }}
                            </td>
                            <td>
                                @php echo $staff->status_badge @endphp
                            </td>

                            @canany(['edit branch staff', 'update branch staff', 'change staff status', 'login as staff'])
                                <td>
                                    <div class="custom--dropdown">
                                        <button type="button" class="btn btn--sm btn--icon btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('edit branch staff')
                                                <li>
                                                    <a href="{{ route('admin.branch.staffs.edit', $staff) }}" class="dropdown-item">
                                                        <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Staff')
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('change staff status')
                                                @if($staff->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.branch.staffs.status', $staff->id) }}" data-question="@lang('Are you sure you want to ban this staff?')">
                                                            <span class="dropdown-icon"><i class="ti ti-user-cancel text--warning"></i></span> @lang('Ban Staff')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.branch.staffs.status', $staff->id) }}" data-question="@lang('Are you sure you want to unban this staff?')">
                                                            <span class="dropdown-icon"><i class="ti ti-user-check text--success"></i></span> @lang('Unban Staff')
                                                        </button>
                                                    </li>
                                                @endif
                                            @endcan

                                            @can('login as staff')
                                                <li>
                                                    <a href="{{ route('admin.branch.staffs.login', $staff) }}" class="dropdown-item">
                                                        <span class="dropdown-icon"><i class="ti ti-login-2 text--info"></i></span> @lang('Login as Staff')
                                                    </a>
                                                </li>
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

        @if ($staffs->hasPages())
            {{ paginateLinks($staffs) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('create branch staff')
        <a href="{{ route('admin.branch.staffs.create') }}" class="btn btn--sm btn--base d-flex align-items-center gap-1">
            <i class="ti ti-circle-plus transform-0"></i> @lang('Create New')
        </a>
    @endcan
@endpush
