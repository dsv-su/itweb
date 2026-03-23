<br>
TO: {{$head->name}}
<br>
SUBJECT: New Proposal Submitted for Review
<br>
------------------------------------------------------------
<br>
Dear {{$head->name}},
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and is now available for your review.
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Proposal:</b> {{$dashboard->name}}
<br>
<b>Requester:</b> {{$user->name}}
<br>
<b>Unit Head(s):</b>
@foreach($dashboard->unit_heads as $head)
    {{ \App\Models\User::find($head)->name }}
    ,
@endforeach
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br><br>
ACTION
<br>
--------------
<br>
Review the proposal using this link:
<br><br>
Direct link to:
<a href="{{ url('') }}/projectproposals/review/{{$dashboard->request_id}}">{{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
