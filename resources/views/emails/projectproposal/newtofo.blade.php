<br>
To: {{$fo->name}}
<br>
SUBJECT: New Project Proposal Submitted for Review
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
<br>
<b>Approved by unit head(s):</b> {{Carbon\Carbon::parse($dashboard->updated_at)->format('Y-m-d')}}
<br>
--------------
<br>
Link to:
<a href="{{ url('') }}/projectproposals/all">ProjectProposals</a>
<br><br>
<!--You can review the details and take necessary action by accessing the proposal through the following link:
<br><br>
<a href="{{-- url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name --}}</a>
<br><br>-->
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
