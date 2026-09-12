@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">@lang('Transaction details')</h5>
                    <p class="text-muted mb-0">{{ $transaction->user->fullname }} &middot; @{{ $transaction->user->username }}</p>
                </div>

                <form action="{{ route('admin.transaction.update', $transaction->id) }}" method="POST" class="row g-4">
                    @csrf
                    <div class="col-md-6">
                        <label class="form--label">@lang('Account number')</label>
                        <input type="text" name="account_number" class="form--control" value="{{ old('account_number', $transaction->account_number ?: $transaction->user->account_number) }}" maxlength="60" required>
                        @error('account_number') <small class="text--danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form--label">@lang('Reference')</label>
                        <input type="text" name="trx" class="form--control" value="{{ old('trx', $transaction->trx) }}" maxlength="255" required>
                        @error('trx') <small class="text--danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form--label">@lang('Date and time')</label>
                        <input type="datetime-local" name="created_at" class="form--control" value="{{ old('created_at', $transaction->created_at->format('Y-m-d\\TH:i')) }}" required>
                        @error('created_at') <small class="text--danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn--base"><i class="ti ti-device-floppy"></i> @lang('Save changes')</button>
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-outline--secondary">@lang('Cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
