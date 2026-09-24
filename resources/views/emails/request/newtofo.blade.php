<div lang="sv">
<strong>Svenska</strong>
<br>
<br>
Till: ekonomihandläggare
<br>
Ämne:  Ny resebegäran inskickad för granskning
<br>
------------------------------------------------------------
<br>
Hej ekonomihandläggare,
<br><br>
En ny <strong>resebegäran</strong> har skickats in och har redan godkänts av både projektledaren/handledaren och enhetschefen.
Din granskning och ditt godkännande krävs nu för att gå vidare.
Vänligen granska resebegäran. Nedan följer en kort översikt:
<br><br>
ÖVERSIKT:
<br>
--------------
<br>
<b>Sökande:</b> {{$user->name}}
<br>
<b>Projektledare/Handledare:</b> {{$manager->name}}
<br>
<b>Enhetschef:</b> {{$head->name}}
<br>
<b>Skapad:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateTimeString()}}
<br>
<b>Begärans ID:</b> {{$dashboard->request_id}}
<br><br>
Du kan granska uppgifterna och vidta nödvändiga åtgärder genom att öppna resebegäran via länken nedan:
<br><br>
ÅTGÄRD
<br>
-----------------------------------------------
<br>
Direktlänk:
<br>
<a href="{{ url('') }}/travel/review/{{$dashboard->id}}">{{$dashboard->name}}</a>
<br><br>
Tack för att du hanterar denna resebegäran snarast.
<br><br>
---
<br>
Detta är ett automatiskt e-postmeddelande. Vänligen svara inte på detta meddelande.
</div>
<hr>
<div lang="en">
<strong>English</strong>
<br>
<br>
To: Financial Officer
<br>
Subject:  New {{$dashboard->type}} Submitted for Review
<br>
------------------------------------------------------------
<br>
Dear Financial Officer,
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and has already been approved from both the projectleader/supervisor and unit head.
Your review and approval are now required to proceed.
Please take a moment to review the request. Below is a brief overview:
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Projectleader/Supervisor:</b> {{$manager->name}}
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
</div>
