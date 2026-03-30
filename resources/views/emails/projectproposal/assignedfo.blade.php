<br>
To: {{$fo->name}}
<br>
Subject: You have been assigned a new Project Proposal for Review
<br>
------------------------------------------------------------
<br><br>
Dear {{$fo->name}},
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and is now available for your review.
<br><br>
OVERVIEW:
<br>
--------------
<br>
<b>Proposal:</b> {{$dashboard->name}}
<br>
<b>Unit Head(s):</b>
@foreach($dashboard->unit_heads as $head)
    {{ \App\Models\User::find($head)->name }}@if(! $loop->last), @endif
@endforeach
<br>
<b>Created:</b> {{Carbon\Carbon::createFromTimestamp($dashboard->created)->format('Y-m-d')}}
<br>
<b>ProposalID:</b> {{$dashboard->request_id}}
<br>
<b>Approved by unit head(s):</b> {{Carbon\Carbon::parse($dashboard->updated_at)->format('Y-m-d')}}
<br><br>
ACTION
<br>
--------------
<br>
Review the proposal using this link:
<br><br>
Direct link to:
<a href="{{ url('') }}/projectproposals/review/{{$dashboard->request_id}}">{{$dashboard->name }}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
