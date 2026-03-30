<br>
To: {{$user->name}}
<br>
Subject: Missing necessary files
<br>
------------------------------------------------------------
<br><br>
Your Project Proposal Submitted for Review is missing necessary files.<br><br>
Dear {{$user->name}},
<br><br>
Your project proposal, submitted on {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}},
is <strong>missing necessary files</strong>. Please upload: <br><br>
<strong> - A budget file </strong>
<br>
<strong> - A brief description of the proposal </strong>
<br>
<strong> - Any other relevant supporting documents </strong>
<br><br>
OVERVIEW:
<br>
------------------
<br>
<b>Proposal:</b> {{$dashboard->name}}
<br><br>
<b>Requester:</b> {{$user->name}}
<br><br>
<b>Unit Head(s):</b>
@foreach($dashboard->unit_heads as $head)
    {{ \App\Models\User::find($head)->name }}@if(! $loop->last), @endif
@endforeach
<br><br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br><br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br><br>
You can review the details and upload the requested files by accessing the proposal through the following link:
<br><br>
Direct link to:
<a href="{{ url('') }}/projectproposals/complete/{{$dashboard->request_id}}#proposal-attachments">{{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
