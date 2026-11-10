@extends('layouts.app')
@section('page-navigation')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
@endsection
@section('content')
    <div class="home-dashboard text-page">
        <div class="home-dashboard__inner">
            @include('layouts.breadcrumbs')
            @include('partials.page')
        </div>
    </div>
@endsection
