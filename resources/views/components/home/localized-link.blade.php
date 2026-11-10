@props(['slug'])

@php
    $entry = \Statamic\Facades\Entry::query()
        ->where('collection', 'it')
        ->where('slug', $slug)
        ->first();

    $localizedEntry = $entry?->in(\Statamic\Facades\Site::current()->handle());
@endphp

@if ($localizedEntry)
    <a href="{{ $localizedEntry->url() }}" {{ $attributes }}>
        {{ $slot }}
        <svg class="w-2.5 h-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
            <path d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
    </a>
@endif
