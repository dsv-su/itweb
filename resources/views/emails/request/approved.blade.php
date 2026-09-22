@if($dashboard->type == 'travelrequest')
<div lang="sv">
<strong>Svenska</strong>
<br>
<br>
Till: {{$user->name}}
<br>
Ämne: GODKÄND RESEBEGÄRAN
<br>
------------------------------------------------------------
<br><br>
Hej {{$user->name}},
<br><br>
Vi har glädjen att meddela att din
@if($dashboard->type == 'projectproposal')
    projektansökan
@else
    resebegäran
@endif

har godkänts
@if($dashboard->type == 'projectproposal')
    för inlämning. Du kan nu skicka in din ansökan.
@else
    .
@endif
<br><br>
ÖVERSIKT:
<br>
--------------
<br>
<b>Ansökan:</b> {{$dashboard->name}}
<br>
<b>Sökande:</b> {{$user->name}}
<br>
<b>Skapad:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>Ansökans ID:</b> {{$dashboard->request_id}}
<br>
<br>
--------------
<br>
@if($dashboard->type == 'travelrequest')
    Trevlig resa
@endif
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
<br>
To: {{$user->name}}
<br>
Subject: APPROVED {{Illuminate\Support\Str::upper($dashboard->type)}}
<br>
------------------------------------------------------------
<br><br>
Dear {{$user->name}},
<br><br>
We are pleased to inform you that your
@if($dashboard->type == 'projectproposal')
    projectproposal
@else
    request
@endif

has been approved
@if($dashboard->type == 'projectproposal')
    for submission. You may now submit your application.
@else
    .
@endif
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Proposal:</b> {{$dashboard->name}}
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br>
<br>
--------------
<br>
@if($dashboard->type != 'travelrequest')
Please manually report your submitted application by clicking the Sent button. Once you receive a grant decision or a rejection, update your progress by clicking the corresponding buttons.

<br><br>
@endif
@if($dashboard->type == 'travelrequest')
    Bon Voyage
@endif
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
</div>
