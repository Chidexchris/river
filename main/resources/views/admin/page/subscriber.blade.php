@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Subscription Date')</th>

                        @can('remove subscriber')
                            <th>@lang('Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscribers as $subscriber)
                        <tr>
                            <td>{{ $subscribers->firstItem() + $loop->index }}</td>
                            <td>{{ $subscriber->email }}</td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($subscriber->created_at) }}</p>
                                    <p>{{ diffForHumans($subscriber->created_at) }}</p>
                                </div>
                            </td>

                            @can('remove subscriber')
                                <td>
                                    <button type="button" class="btn btn--sm btn--danger decisionBtn" data-question="@lang('Are you confirm to remove this subscriber')?" data-action="{{ route('admin.subscriber.remove', $subscriber->id) }}">
                                        <i class="ti ti-trash"></i> @lang('Delete')
                                    </button>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subscribers->hasPages())
            {{ paginateLinks($subscribers) }}
        @endif
    </div>

    {{-- Email Modal --}}
    <div class="custom--modal modal fade" id="sendMailModal" tabindex="-1" aria-labelledby="sendMailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="sendMailModalLabel">@lang('Email to Subscribers')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('admin.subscriber.send.email') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form--label required">@lang('Subject')</label>
                                <input type="text" class="form--control" name="subject" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Body')</label>
                                <textarea name="body" class="trumEdit"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-outline--base" data-bs-dismiss="modal">
                            @lang('Close')
                        </button>
                        <button type="submit" class="btn btn--base">
                            <i class="ti ti-send"></i> @lang('Send')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Email" />

    @can('send email to subscribers')
        <button type="button" class="btn btn--sm btn--base" data-bs-target="#sendMailModal" data-bs-toggle="modal">
            <i class="ti ti-mail"></i> @lang('Send Mail')
        </button>
    @endcan
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            if ($(".trumEdit")[0]) {
                ClassicEditor
                    .create(document.querySelector('.trumEdit'))
                    .then(editor => {
                        window.editor = editor;
                    });
            }
        })(jQuery);
    </script>
@endpush
