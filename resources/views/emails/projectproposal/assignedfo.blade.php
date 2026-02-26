<b>PLEASE NOTE! </b>
<br>
<b>ProjectProposal is currently in test mode, and all created proposals are fictitious and intended for testing purposes only.</b>
<br><br>
------------------------------------------------------------
<br>
TO: Financial officer, {{$fo->name}}
<br>
SUBJECT: You have been assigned a new Project Proposal for Review
<br>
------------------------------------------------------------
<br>
<br><br>
Dear Finacial Officer,
<br><br>
A new <strong>{{Illuminate\Support\Str::upper($dashboard->type)}}</strong> has been submitted and is now available for your review.
<br><br>
OVERVIEW:
<br>
<b>Proposal:</b> {{$dashboard->name}}
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
<br><br>
ACTION
<br>
Review the proposal using this link:
<br><br>
<a href="{{ url('') }}/pp/review/{{$dashboard->request_id}}">Direct link to {{$dashboard->name }}</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
