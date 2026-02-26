Review Request: New Request - <b>Action Required</b><br><br>
Dear {{$manager->name}},
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted.
Your attention is kindly requested to review and approve the request. Here's a quick overview of the request:
<br><br>
<b>Requester:</b> {{$user->name}}
<br><br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateTimeString()}}
<br><br>
<b>Projectleader/Manager:</b> {{$manager->name}}
<br><br>
<b>Unit Head:</b> {{$head->name}}
<br><br>
<b>RequestID:</b> {{$dashboard->request_id}}
<br><br>
Please review the request at your earliest convenience by accessing the following link:
<br><br>
<a href="{{ url('') }}">{{url('')}}</a>
<br><br>
Thank you for your prompt attention to this request.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
