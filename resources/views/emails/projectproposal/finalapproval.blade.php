<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
To: ViceHead
<br>
SUBJECT: FINAL APPROVAL
<br>
------------------------------------------------------------
<br><br>
A new <strong>{{$dashboard->type}}</strong> awaits your Final Approval.
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
Review the proposal using this link:
<br><br>
<a href="{{ url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
