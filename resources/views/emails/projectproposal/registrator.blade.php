<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
TO: Registrator DSV
<br>
SUBJECT: Application with budget
<br>
------------------------------------------------------------
<br>
<br><br>
Hereby is the application for the project titled <b>{{$dashboard->name}}</b> together with the associated budget documentation.
Both documents are attached to this email for registration and further processing.
<br><br>
OVERVIEW:
<br>
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
