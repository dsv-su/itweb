Notification: <b>Status Update on Your Submitted Request</b><br><br>
Dear {{$user->name}}
<br><br>
We're reaching out to inform you that your <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been processed.
<br><br>
<b>Updated:</b> {{$dashboard->updated_at}}
<br><br>
<b>By:</b> {{$return->name}}
<br><br>
<b>Status:</b>
@switch($dashboard->state)
    @case('manager_returned')
        RETURNED
        @break
    @case('vice_returned')
        RETURNED
        @break
    @case('fo_returned')
        RETURNED
        @break
    @case('head_returned')
        RETURNED
        @break
    @case('manager_denied')
        DENIED
        @break
    @case('vice_denied')
        DENIED
        @break
    @case('fo_denied')
        DENIED
        @break
    @case('head_denied')
        DENIED
        @break
@endswitch

<br><br>
Please take a moment to review any associated comments at your earliest convenience by visiting:
<br><br>
<a href="{{ url('') }}">{{url('')}}</a>
<br><br>
Should you have any questions or need further assistance, feel free to reach out to {{$return->name}}. However, please note that this is an automated notification, and replies to this email will not be monitored.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
