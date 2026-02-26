<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
TO: Unithead
<br>
SUBJECT: A Proposal has been ReSubmitted for Review
<br>
------------------------------------------------------------
<br>
<br><br>
Dear {{$head->name}},
<br><br>
A <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been resubmitted and is now available for your review.
<br><br>
OVERVIEW:
<br><br>
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
Review the proposal using this link:
<br><br>
<a href="{{ url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name}}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
