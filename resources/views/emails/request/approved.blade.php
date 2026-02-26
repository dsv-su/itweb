<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
To: {{$user->name}}
<br>
SUBJECT: APPROVED {{Illuminate\Support\Str::upper($dashboard->type)}}
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
<br><br>
<b>Proposal:</b> {{$dashboard->name}}
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br><br>
ACTION
<br>
Please manually report your submitted application by clicking the Sent button. Once you receive a grant decision or a rejection, update your progress by clicking the corresponding buttons.

<br><br>
@if($dashboard->type == 'travelrequest')
    Bon Voyage
@endif
<br><br>
---
<br>
This is an automated email, please do not reply to this email.


