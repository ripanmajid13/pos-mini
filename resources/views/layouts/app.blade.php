@extends('layouts.base')

@section('base-class')
    sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed
@endsection

@section('body')
    <div class="wrapper">
        <x-layouts.navbar />
        <x-layouts.sidebar-main />
        <div class="content-wrapper">
            <x-layouts.content-header />
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <x-layouts.footer />
        <x-layouts.sidebar-control />
    </div>
@endsection
