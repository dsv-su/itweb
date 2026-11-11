@extends('layouts.app')
@section('page-navigation')
    @nocache('dsvheader')
    @nocache('navbar.navbar')
@endsection
@section('content')
    <div class="home-dashboard text-page news-page">
        <div class="home-dashboard__inner">
            <article class="home-card text-page__article prose prose-slate dark:prose-invert" aria-labelledby="news-title">
                <div class="text-page__body">
                    <header class="text-page__header">
                        <div class="not-prose news-page__meta">
                            <span>{{ $page->date }}</span>
                            <span class="news-page__badge">{{ $page->collection }}</span>
                        </div>
                        <h1 id="news-title">{{ $page->title }}</h1>
                        @if($page->author->name ?? null)
                            <p class="not-prose news-page__author">{{ $page->author->name }}</p>
                        @endif
                    </header>
                    <div class="page-rich-content">{!! $page->content !!}</div>
                </div>
            </article>
        </div>
    </div>
@endsection
