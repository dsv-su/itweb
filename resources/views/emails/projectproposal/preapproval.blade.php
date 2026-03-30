<br>
To: ViceHead
<br>
Subject: New <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> submitted for review.
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
Direct link to:
<a href="{{ url('') }}/projectproposals/review/{{$dashboard->request_id}}">{{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
