@switch($proposal->status_stage2)
    @case('pending')
    @php
        $state = 'PENDING';
        $bgcolor = 'bg-gray-100';
        $textcolor = 'text-gray-800';
    @endphp
    @break
    @case('vice_approved')
    @php
        $state = 'WAITING';
        $bgcolor = 'bg-blue-100';
        $textcolor = 'text-blue-800';
    @endphp
    @break
    @case('uploaded')
    @php
        $state = 'REVIEW';
        $bgcolor = 'bg-purple-100';
        $textcolor = 'text-purple-800';
    @endphp
    @break
    @case('fo_approved')
    @php
        $state = 'PREAPPROVED';
        $bgcolor = 'bg-green-100';
        $textcolor = 'text-green-800';
    @endphp
    @break
    @default
    @php
        $state = 'ERROR';
        $bgcolor = 'bg-red-100';
        $textcolor = 'text-yellow-800';
    @endphp
    @break
@endswitch
<span class="{{$bgcolor}} {{$textcolor}} text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
    {{$proposal->dashboard->state}}
</span>
