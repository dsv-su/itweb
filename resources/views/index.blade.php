@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
    @include('home.partials.banner')
    @include('home.welcome')
@endsection
