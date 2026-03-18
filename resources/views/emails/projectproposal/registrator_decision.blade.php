<br>
TO: Registrator DSV
<br>
SUBJECT: Decision letter application, {{$dashboard->name}}
<br>
------------------------------------------------------------
<br>
<br><br>
Hereby is the decision letter for application for the project titled <b>{{$dashboard->name}}</b>.
The document is attached to this email for registration and further processing.
<br><br>
OVERVIEW:
<br><br>
<b>Application:</b> {{$dashboard->name}}
<br>
<b>Principal Investigator:</b> {{$user->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
