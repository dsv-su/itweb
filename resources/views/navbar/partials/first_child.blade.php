@foreach (\Statamic\Statamic::tag('nav:main')->sort('order')->fetch() as $entry)
    @if($entry['children'])
        @foreach(collect($entry['children']) as $child)
            @if($child['is_current'])
                @include('navbar.partials.second_child')
            @endif
        @endforeach
    @endif
@endforeach
