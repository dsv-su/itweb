@if($dashboard->type == 'travelrequest')
<div lang="sv">
<strong>Svenska</strong>
<br>
Till: {{$user->name}}
<br>
Ämne: <b>Statusuppdatering för din inskickade resebegäran</b>
<br>
------------------------------------------------------------
<br><br>
Hej  {{$user->name}}
<br><br>
Vi vill informera dig om att din <strong>resebegäran</strong> har granskats och behandlats.
<br><br>
<b>Senast uppdaterad:</b> {{$dashboard->updated_at}}
<br><br>
<b>Granskad av:</b> {{$return->name}}
<br><br>
<b>Aktuell status:</b>
@switch($dashboard->state)
    @case('manager_returned')
        ÅTERLÄMNAD FÖR KOMPLETTERING
        @break
    @case('vice_returned')
    @case('final_returned')
        ÅTERLÄMNAD FÖR KOMPLETTERING
        @break
    @case('fo_returned')
        ÅTERLÄMNAD FÖR KOMPLETTERING
        @break
    @case('head_returned')
        ÅTERLÄMNAD FÖR KOMPLETTERING
        @break
    @case('manager_denied')
        AVSLAGEN
        @break
    @case('vice_denied')
        AVSLAGEN
        @break
    @case('fo_denied')
        AVSLAGEN
        @break
    @case('head_denied')
        AVSLAGEN
        @break
@endswitch

<br><br>
Vänligen läs kommentarerna via länkarna nedan:
<br><br>

@if($dashboard->type == 'projectproposal')
    <b>Direktlänk för visning (skrivskyddad):</b>
    <br><br>
    <a href="{{ url('') }}/projectproposals/review/view/{{$dashboard->request_id}}">VISA: {{$dashboard->name}}</a>
    <br><br>
    <b>Direktlänk för att återuppta. Uppdatera din ansökan, ladda upp eventuella filer som krävs och skicka in igen:</b>
    <br><br>
    <a href="{{ url('') }}/projectproposals/resume/{{$dashboard->request_id}}">ÅTERUPPTA: {{$dashboard->name}}</a>
@else
    Direktlänk:
    <br>
    <a href="{{ url('') }}">{{url('')}}</a>
@endif
<br><br>
Om du har frågor eller behöver ytterligare hjälp är du välkommen att kontakta <i>{{$return->name}}</i> direkt.
Observera att detta är ett automatiskt meddelande och att svar på detta e-postmeddelande inte bevakas.
<br><br>
---
<br>
Detta är ett automatiskt e-postmeddelande. Vänligen svara inte på detta meddelande.
</div>
<hr>
@endif
<div lang="en">
@if($dashboard->type == 'travelrequest')
<strong>English</strong>
<br>
@endif
To: {{$user->name}}
<br>
Subject: <b>Status Update on Your Submitted Request</b>
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
    @case('final_returned')
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
Please take a moment to review the related comments using the links below:
<br><br>

@if($dashboard->type == 'projectproposal')
    <b>View direct link(read-only):</b>
    <br><br>
    <a href="{{ url('') }}/projectproposals/review/view/{{$dashboard->request_id}}">VIEW: {{$dashboard->name}}</a>
    <br><br>
    <b>Resume direct link. Update your proposal, upload any required files, and resubmit:</b>
    <br><br>
    <a href="{{ url('') }}/projectproposals/resume/{{$dashboard->request_id}}">RESUME: {{$dashboard->name}}</a>
@else
    Direct link:
    <br>
    <a href="{{ url('') }}">{{url('')}}</a>
@endif
<br><br>
If you have any questions or need further assistance, you’re welcome to contact <i>{{$return->name}}</i> directly.
Please note that this is an automated message and replies to this email are not monitored.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
</div>
