@extends('layouts.app')
@section('page-navigation')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
@endsection
@section('content')
    <div class="home-dashboard news-page">
        <div class="home-dashboard__inner">
            <header class="news-page__header">
                <span class="home-icon home-icon--blue"><x-home.icon name="news" /></span>
                <h1>{{ __('News') }}</h1>
                <p>{{ __('Latest entries') }}</p>
            </header>
            @include('news.partials.entries', ['entries' => $collections])
        </div>
    </div>
@endsection
