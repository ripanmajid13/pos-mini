@extends('layouts.base')

@section('base-class')
    @yield('body-class')
@endsection

@push('style_inline')
    <style>
        .login-page, .register-page {
            background-image: url("{{ asset('images/default/bg-auth.jpg') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 90vh;
        }
    </style>
@endpush

@section('body')
    @yield('content')
@endsection
