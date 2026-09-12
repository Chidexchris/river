@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.roles.store') }}" method="post">
            @csrf
            <div class="custom--card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form--label required">@lang('Name')</label>
                            <input type="text" class="form--control" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom--card mt-4">
                <div class="card-header">
                    <h6 class="card-title">@lang('Assign Permissions')</h6>
                </div>

                @foreach($permissions as $module => $groupedPermissions)
                    <div @class(['card-body', 'border-top' => !$loop->first])>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form--switch">
                                        <input type="checkbox" class="form-check-input module-checkbox" data-module="{{ $module }}">
                                    </div>
                                    <label class="col-form--label fw-bold fs-6">{{ __($module) }}</label>
                                </div>
                            </div>

                            @foreach($groupedPermissions as $permission)
                                <div class="col-lg-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form--switch">
                                            <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]" value="{{ $permission->id }}" data-module="{{ $module }}" @checked(in_array($permission->id, old('permissions', [])))>
                                        </div>
                                        <label class="col-form--label">{{ __($permission->name) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @can('store role')
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                @endcan
            </div>
        </form>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.roles.index') }}" class="btn btn--sm btn--base">
        <i class="ti ti-circle-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                // Function to check or uncheck module checkboxes based on permissions
                function updateModuleCheckbox(module) {
                    let allChecked = $(`.permission-checkbox[data-module="${module}"]`).length ===
                                    $(`.permission-checkbox[data-module="${module}"]:checked`).length

                    $(`.module-checkbox[data-module="${module}"]`).prop('checked', allChecked)
                }

                let moduleCheckbox = $('.module-checkbox')

                // Initialize on page load
                moduleCheckbox.each(function () {
                    let module = $(this).data('module')

                    updateModuleCheckbox(module)
                })

                // Toggle all permission checkboxes when a module checkbox is checked/unchecked
                moduleCheckbox.on('change', function () {
                    let module = $(this).data('module')
                    let isChecked = $(this).is(':checked')

                    $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked)
                })

                // Toggle module checkbox based on individual permissions
                $('.permission-checkbox').on('change', function () {
                    let module = $(this).data('module')

                    updateModuleCheckbox(module)
                })
            })
        })(jQuery)
    </script>
@endpush
