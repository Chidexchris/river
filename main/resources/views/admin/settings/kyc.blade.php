@extends('admin.layouts.master')

@section('master')
    <form action="" method="POST">
        @csrf

        @include('admin.partials.formData')
    </form>

    <x-formGenerator/>
@endsection
