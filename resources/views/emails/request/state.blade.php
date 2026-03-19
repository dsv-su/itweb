TO: {{$user->name}}
<br>
SUBJECT: <b>Status Update on Your Submitted Request</b>
<br>
------------------------------------------------------------
<br><br>
Dear  {{$user->name}}
<br><br>
We are writing to inform you that your <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been reviewed and processed.
<br><br>
<b>Last updated:</b> {{$dashboard->updated_at}}
<br><br>
<b>Reviewed By:</b> {{$return->name}}
<br><br>
<b>Current Status:</b>
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
Please take a moment to review any associated comments by visiting the link below:
<br><br>
<a href="{{ url('') }}">{{url('')}}</a>
<br><br>
If you have any questions or need further assistance, you’re welcome to contact {{$return->name}} directly.
Please note that this is an automated message and replies to this email are not monitored.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
