@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <div class="custom--card">
                        <div class="card-body">
                            <div class="row g-lg-4 g-3">
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Short Details')</label>
                                        </div>
                                        <div class="col-xxl-9">
                                            <textarea class="form--control" name="short_details" required>{{ $cookie?->data_info->short_details }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Details')</label>
                                        </div>
                                        <div class="col-xxl-9 editor-wrapper">
                                            <textarea class="form--control trumEdit" name="details" required>{{ $cookie?->data_info->details }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Status')</label>
                                        </div>
                                        <div class="col-xxl-9">
                                            <div class="form-check form--switch">
                                                <input type="checkbox" class="form-check-input" name="status" @checked($cookie?->data_info->status)>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @can('update cookie settings')
                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            if ($(".trumEdit")[0]) {
                $('.editor-wrapper').find('.ck-editor').remove();

                window.editors = {};
                document.querySelectorAll('.trumEdit').forEach((node, index) => {
                    ClassicEditor
                        .create(node)
                        .then(newEditor => {
                            window.editors[index] = newEditor;
                        });
                });
            }
        })(jQuery);
    </script>
@endpush
