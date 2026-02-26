<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
To: {{$user->name}}
<br>
SUBJECT: Reminder: Please Report Sent
<br>
------------------------------------------------------------
<br><br>
Dear {{$user->name}},
<br><br>
This is a friendly reminder to please update your proposal and mark it as <strong>“Sent”</strong> once you have submitted your application.
<br><br>
If you have already reported your proposal status, please disregard this message.
<br>
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
<a href="{{ url('') }}/pp/my">Your proposals</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
