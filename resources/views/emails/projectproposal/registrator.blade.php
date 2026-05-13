<br>
To: Registrator DSV
<br>
Subject: Application with budget
<br>
------------------------------------------------------------
<br>
<br><br>
Hereby is the application for the project titled <b>{{$dashboard->name}}</b> together with the associated budget documentation.
Both documents are attached to this email for registration and further processing.
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Application:</b> {{$dashboard->name}}
<br>
<b>Principal Investigator at DSV:</b> {{$user->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}

<br><br>
Proposal details:
<br>
--------------
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br>
<b>Funding organization:</b> {{ data_get($proposal, 'pp.funding_organization', 'N/A') }}
<br>
<b>Submission deadline:</b> {{ data_get($proposal, 'pp.submission_deadline', 'N/A') }}
<br>
<b>Budget (project):</b> {{ data_get($proposal, 'pp.budget_project', 'N/A') }} {{ strtoupper((string) data_get($proposal, 'pp.currency', '')) }}
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
