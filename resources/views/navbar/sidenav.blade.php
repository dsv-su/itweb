<!-- nav:collection:pages -->
@foreach (\Statamic\Statamic::tag('nav:main')->sort('order')->fetch() as $entry)
    @if($entry['children'])
        @include('navbar.partials.children')
    @endif
@endforeach

