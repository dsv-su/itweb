<br>
To: ViceHead
<br>
Subject: Granted Proposal
<br>
------------------------------------------------------------
<br>
<br><br>
This is an automated notification to inform you that application, <strong>{{$dashboard->name}}</strong>, has been granted.
Please find the relevant details through the following link:

<br><br>
Direct link to:
<a href="{{ url('') }}/projectproposals/view/{{$dashboard->request_id}}">{{$dashboard->name}}</a>
<br><br>
<br>
OVERVIEW:
<br><br>
<b>Proposal:</b> {{$dashboard->name}}
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
