<div lang="sv">
<strong>Svenska</strong>
<br>
<br>
Till: {{$manager->name}}
<br>
Ämne: Ny resebegäran inskickad för granskning
<br>
------------------------------------------------------------
<br>
Hej {{$manager->name}},
<br><br>
En ny <strong>resebegäran</strong> har skickats in och är nu tillgänglig för din granskning.
<br><br>
ÖVERSIKT:
<br>
--------------
<br><br>
RESEBEGÄRAN
<br><br>
<b>Sökande:</b> {{$user->name}}
<br><br>
<b>Skapad:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateTimeString()}}
<br><br>
<b>Projektledare/Handledare:</b> {{$manager->name}}
<br><br>
<b>Enhetschef:</b> {{$head->name}}
<br><br>
<b>Begärans ID:</b> {{$dashboard->request_id}}
<br><br>
ÅTGÄRD
<br>
--------------
<br>
Granska resebegäran via denna länk:
<br><br>
<a href="{{ route('travel-request-review', $dashboard->id) }}">{{$dashboard->name}}</a>
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
To: {{$manager->name}}
<br>
Subject: New {{$dashboard->type}} Submitted for Review
<br>
------------------------------------------------------------
<br>
Dear {{$manager->name}},
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and is now available for your review.
<br><br>
OVERVIEW:
<br>
--------------
<br><br>
{{Illuminate\Support\Str::upper($dashboard->type)}}
<br><br>
<b>Requester:</b> {{$user->name}}
<br><br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->toDateTimeString()}}
<br><br>
<b>Projectleader/Supervisor:</b> {{$manager->name}}
<br><br>
<b>Unit Head:</b> {{$head->name}}
<br><br>
<b>RequestID:</b> {{$dashboard->request_id}}
<br><br>
ACTION
<br>
--------------
<br>
Review the request using this link:
<br><br>
<a href="{{ route('travel-request-review', $dashboard->id) }}">{{$dashboard->name}}</a>
<br><br>
Thank you for your prompt attention to this request.
<br><br>
---
<br>
This is an automated email, please do not reply to this email.
</div>
