@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
    @include('home.partials.banner')
    @include('home.home')
    @nocache('layouts.darktoggler')
@endsection
