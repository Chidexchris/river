@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Username')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Role')</th>
                        <th>@lang('Status')</th>

                        @canany(['edit admin', 'change admin status', 'login as admin'])
                            <th>@lang('Action')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admins->firstItem() + $loop->index }}</td>
                            <td>{{ __($admin->name) }}</td>
                            <td>{{ __($admin->username) }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                {{ $admin->roles->pluck('name')->map(fn($name) => __($name))->implode(', ') }}
                            </td>
                            <td>
                                @php echo $admin->status_badge @endphp
                            </td>

                            @canany(['edit admin', 'change admin status', 'login as admin'])
                                <td>
                                    @if(!$admin->hasRole('Super Admin'))
                                        <div class="custom--dropdown">
                                            <button type="button" class="btn btn--sm btn--icon btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @can('edit admin')
                                                    <li>
                                                        <button type="button" class="dropdown-item btn-edit" data-action="{{ route('admin.staffs.update', $admin) }}" data-name="{{ $admin->name }}" data-username="{{ $admin->username }}" data-email="{{ $admin->email }}" data-roles="{{ $admin->roles }}">
                                                            <span class="dropdown-icon"><i class="ti ti-edit text--base"></i></span> @lang('Edit Admin')
                                                        </button>
                                                    </li>
                                                @endcan

                                                @can('change admin status')
                                                    @if($admin->status)
                                                        <li>
                                                            <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.staffs.status', $admin->id) }}" data-question="@lang('Are you sure you want to ban this admin?')">
                                                                <span class="dropdown-icon"><i class="ti ti-user-cancel text--warning"></i></span> @lang('Ban Admin')
                                                            </button>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button type="button" class="dropdown-item decisionBtn" data-action="{{ route('admin.staffs.status', $admin->id) }}" data-question="@lang('Are you sure you want to unban this admin?')">
                                                                <span class="dropdown-icon"><i class="ti ti-user-check text--success"></i></span> @lang('Unban Admin')
                                                            </button>
                                                        </li>
                                                    @endif
                                                @endcan

                                                @can('login as admin')
                                                    <li>
                                                        <a href="{{ route('admin.staffs.login', $admin) }}" class="dropdown-item">
                                                            <span class="dropdown-icon"><i class="ti ti-login-2 text--info"></i></span> @lang('Login as Admin')
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($admins->hasPages())
            {{ $admins->links() }}
        @endif
    </div>

    {{-- Create Modal --}}
    <div class="custom--modal modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="createModalLabel">@lang('Add New Admin')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Username')</label>
                                <input type="text" class="form--control" name="username" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Email Address')</label>
                                <input type="email" class="form--control" name="email" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Role')</label>
                                <select class="form--control form-select select-2" name="roles[]" multiple required>
                                    <option disabled>@lang('Select Roles')</option>

                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ __($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Password')</label>
                                <div class="input--group">
                                    <input type="password" class="form--control" id="createPassword" name="password" required>
                                    <button type="button" class="btn btn--base btn-generate-password" data-input_id="createPassword">
                                        @lang('Generate')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="custom--modal modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="editModalLabel">@lang('Edit Admin')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Username')</label>
                                <input type="text" class="form--control" name="username" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Email Address')</label>
                                <input type="email" class="form--control" name="email" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Role')</label>
                                <select class="form--control form-select select-2" name="roles[]" multiple required>
                                    <option disabled>@lang('Select Roles')</option>

                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ __($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('Password')</label>
                                <div class="input--group">
                                    <input type="password" class="form--control" id="editPassword" name="password">
                                    <button type="button" class="btn btn--base btn-generate-password" data-input_id="editPassword">
                                        @lang('Generate')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    @can('create admin')
        <button type="button" class="btn btn--sm btn--base" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-circle-plus"></i> @lang('Add New')
        </button>
    @endcan
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            // Function to generate a random password
            function generatePassword(length = 12) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:,.<>?'
                let password = ''

                for (let i = 0; i < length; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length))
                }

                return password
            }

            $(function () {
                // Bind generate password button in both modals dynamically
                $('.modal').on('click', '.btn-generate-password', function () {
                    const newPassword = generatePassword()
                    const inputID = $(this).data('input_id')

                    $(this).closest('.modal').find(`#${inputID}`).val(newPassword)
                })

                $('.btn-edit').on('click', function () {
                    let data = $(this).data()
                    let editModal = $('#editModal')

                    editModal.find('form').attr('action', data.action)
                    editModal.find('[name=name]').val(data.name)
                    editModal.find('[name=username]').val(data.username)
                    editModal.find('[name=email]').val(data.email)

                    let roleIds = data.roles.map(role => role.id)

                    editModal.find('[name="roles[]"]').val(roleIds).select2({
                        containerCssClass: ":all:",
                        dropdownParent: editModal.find('[name="roles[]"]').parents('.modal'),
                    })

                    editModal.modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
