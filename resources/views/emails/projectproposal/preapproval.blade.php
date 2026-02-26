<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
TO: ViceHead
<br>
SUBJECT: New <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> submitted for review.
<br>
------------------------------------------------------------
<br>
<br><br>
OVERVIEW:
<br><br>
<b>Proposal:</b> {{$dashboard->name}}
<br><br>
<b>Requester:</b> {{$user->name}}
<br><br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br><br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br><br>
ACTION
<br>
Review the request using this link:
<br><br>
<a href="{{ url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
