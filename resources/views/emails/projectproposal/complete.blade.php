<br>
To: User {{$user->name}}
<br>
SUBJECT: Your project proposal, <i><strong>{{$dashboard->name}}</strong></i>, is now pending completion.
------------------------------------------------------------
<br><br>
Dear {{$user->name}},
<br><br>

To proceed with approval from the Unit Head(s) and Financial Officer,
please update your proposal to include the required dates and budget details.
<br><br>
Additionally, kindly upload the following for review:
<br><br>
<strong> - A budget file </strong>
<br>
<strong> - A brief description of the proposal </strong>
<br>
<strong> - Any other relevant supporting documents </strong>
<br><br>

<strong>Important: </strong>Preapproval from the Vice Head does not guarantee final approval.
    The proposal must still go through all required approval steps before receiving final confirmation.

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
You can review the details and take necessary action by accessing the proposal through the following link:
<br><br>
Direct link to:
<a href="{{ url('') }}/projectproposals/complete/{{$dashboard->request_id}}">{{$dashboard->name}} for updating.</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
