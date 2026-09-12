@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table-borderless table--striped table--responsive--sm custom-data-table">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Subject')</th>

                        @can('edit template')
                            <th>@lang('Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ __($template->name) }}</td>
                            <td>{{ __($template->subj) }}</td>

                            @can('edit template')
                                <td>
                                    <a href="{{ route('admin.notification.template.edit', $template->id) }}" class="btn btn--sm btn-outline--base">
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
    </div>
@endsection

@push('breadcrumb')
    <div class="input--group">
        <input type="search" class="form--control form--control--sm" name="search_table" placeholder="Name/Subject">
        <button type="submit" class="btn btn--sm btn--icon btn--base">
            <i class="ti ti-search"></i>
        </button>
    </div>
@endpush
