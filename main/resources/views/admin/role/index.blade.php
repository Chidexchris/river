@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Creation Date')</th>

                        @can('edit role')
                            <th>@lang('Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $roles->firstItem() + $loop->index }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($role->created_at) }}</p>
                                    <p>{{ diffForHumans($role->created_at) }}</p>
                                </div>
                            </td>

                            @can('edit role')
                                <td>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn--sm btn-outline--base">
                                        <i class="ti ti-edit"></i> @lang('Edit')
                                    </a>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            {{ $roles->links() }}
        @endif
    </div>
@endsection

@can('create role')
    @push('breadcrumb')
        <a href="{{ route('admin.roles.create') }}" class="btn btn--sm btn--base">
            <i class="ti ti-circle-plus"></i> @lang('Create New')
        </a>
    @endpush
@endcan
