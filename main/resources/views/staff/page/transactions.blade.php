@extends('staff.layouts.master')

@section('master')
    <div class="col-12">
        @include('staff.partials.transactionTable')

        @if ($transactions->hasPages())
            {{ paginateLinks($transactions) }}
        @endif
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Here..." dateSearch="yes" />
@endpush
