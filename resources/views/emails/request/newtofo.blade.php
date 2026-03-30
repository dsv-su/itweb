<br>
To: Financial Officer
<br>
Subject:  New {{$dashboard->type}} Submitted for Review
<br>
------------------------------------------------------------
<br>
Dear Financial Officer,
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and has already been approved from both the project leader/manager and unit head.
Your review and approval are now required to proceed.
Please take a moment to review the request. Below is a brief overview:
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Projectleader/Manager:</b> {{$manager->name}}
<br>
<b>Unit Head:</b> {{$head->name}}
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateTimeString()}}
<br>
<b>RequestID:</b> {{$dashboard->request_id}}
<br><br>
You can conveniently review the details and take the necessary action by accessing the request through the link below:
<br><br>
ACTION
<br>
-----------------------------------------------
<br>
Direct link:
<br>
<a href="{{ url('') }}/travel/review/{{$dashboard->id}}">{{$dashboard->name}}</a>
<br><br>
Thank you for your prompt attention to this request.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
