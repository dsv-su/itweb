<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
To: {{$head->name}}
<br>
SUBJECT: Reminder: Please review <strong><i>{{$dashboard->name}}</i></strong>
<br>
------------------------------------------------------------
<br><br>
Dear {{$head->name}},
<br><br>
This is a friendly reminder to please review the proposal, <strong><i>{{$dashboard->name}}</i></strong>, at your earliest convenience.
In order for the proposal to be processed and reviewed by the finance officers, your review is required.
If you would like the applicant to complete or modify any part of the submission, please leave a comment and then select RETURN. Otherwise, you may choose to DENY or APPROVE the proposal.
<br><br>
Thank you in advance for your prompt attention to this matter.
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
You can review the details and take necessary action by accessing the proposal through the following link:
<br><br>
<a href="{{ url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
