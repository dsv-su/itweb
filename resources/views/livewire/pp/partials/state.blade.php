@switch((string) $proposal->dashboard?->state ?? '')
    @case('pending')
        @php
            $state = 'PENDING';
            $bgcolor = 'bg-gray-100';
            $textcolor = 'text-gray-800';
        @endphp
        @break

    @case('submitted')
        @php
            $state = 'AWAITING';
            $bgcolor = 'bg-yellow-100';
            $textcolor = 'text-yellow-800';
        @endphp
        @break

    @case('complete')
        @php
            $state = 'SUBMITTED';
            $bgcolor = 'bg-yellow-100';
            $textcolor = 'text-yellow-800';
        @endphp
        @break

    @case('head_approved')
    @case('fo_approved')
        @php
            $state = 'PROCESSING';
            $bgcolor = 'bg-blue-100';
            $textcolor = 'text-blue-800';
        @endphp
        @break

    @case('final_approved')
        @php
            $state = 'AWAITING';
            $bgcolor = 'bg-green-100';
            $textcolor = 'text-green-800';
        @endphp
        @break

    @case('sent')
        @php
            $state = 'SENT';
            $bgcolor = 'bg-green-100';
            $textcolor = 'text-green-800';
        @endphp
        @break

    @case('granted')
        @php
            $state = 'GRANTED';
            $bgcolor = 'bg-purple-50';
            $textcolor = 'text-purple-700';
        @endphp
        @break

    @case('head_denied')
    @case('fo_denied')
    @case('denied')
        @php
            $state = 'DENIED';
            $bgcolor = 'bg-red-100';
            $textcolor = 'text-red-800';
        @endphp
        @break

    @case('head_returned')
    @case('fo_returned')
    @case('final_returned')
        @php
            $state = 'RETURNED';
            $bgcolor = 'bg-yellow-100';
            $textcolor = 'text-yellow-800';
        @endphp
        @break

    @default
        @php
            $state = 'ERROR';
            $bgcolor = 'bg-red-100';
            $textcolor = 'text-red-800';
        @endphp
        @break
@endswitch

<span
    class="{{ $bgcolor }} {{ $textcolor }}
         inline-flex items-center
         text-[0.65rem] sm:text-xs font-medium
         px-2 py-0.5 sm:px-2.5 sm:py-0.5
         rounded
         border border-current
         whitespace-nowrap">
  {{ $state }}
</span>
