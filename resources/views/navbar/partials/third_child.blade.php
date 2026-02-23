@foreach(collect($sec_child['children']) as $third_child)
    <li>
        <a href="{{ $third_child['url'] }}" title="#" class="inline-flex items-center w-full p-2 pl-3 text-sm
            text-gray-500 rounded-lg hover:text-blue-500 group hover:bg-gray-50 dark:text-white hover:dark:text-blue-500">
            <span class="inline-flex items-center w-full">
                <ion-icon class="w-4 h-4 md hydrated" name="document-outline" role="img" aria-label="document outline"></ion-icon>
                <span class="ml-4">
                    {{ $third_child['title'] }}
                </span>
            </span>
        </a>
    </li>
@endforeach
