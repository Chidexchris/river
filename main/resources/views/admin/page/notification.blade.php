@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Title')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $notification)
                        <tr>
                            <td>
                                <a href="{{ route('admin.system.notification.read', $notification->id) }}">{{ __($notification->title) }}</a>
                            </td>
                            <td>
                                @if ($notification->is_read)
                                    <span class="badge badge--success">@lang('Read')</span>
                                @else
                                    <span class="badge badge--warning">@lang('Unread')</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($notification->created_at) }}</p>
                                    <p>{{ diffForHumans($notification->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    @if ($notification->click_url != '#')
                                        <a href="{{ route('admin.system.notification.read', $notification->id) }}" class="btn btn--sm btn-outline--base">
                                            <i class="ti ti-eye"></i> @lang('Check')
                                        </a>
                                    @endif

                                    <button class="btn btn--sm btn-outline--danger decisionBtn" data-question="@lang('Are you confirming the removal of this notification')?" data-action="{{ route('admin.system.notification.remove', $notification->id) }}">
                                        <i class="ti ti-trash"></i> @lang('Delete')
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($notifications->hasPages())
            {{ paginateLinks($notifications) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@pushif(count($notifications), 'breadcrumb')
    <button class="btn btn--sm btn--base decisionBtn" data-question="@lang('Are you sure you want to mark all the notifications as read?')" data-action="{{ route('admin.system.notification.read.all') }}">
        <i class="ti ti-checks"></i> @lang('Mark all as read')
    </button>
    <button class="btn btn--sm btn--danger decisionBtn" data-question="@lang('Are you confirming the removal of all notifications?')" data-action="{{ route('admin.system.notification.remove.all') }}">
        <i class="ti ti-trash"></i> @lang('Remove All')
    </button>
@endpushif
