<br>
To: User {{ $userName }}
<br>
Subject: Your project proposal, <i><strong>{{ $proposalName }}</strong></i>, has been deleted.
<br>
------------------------------------------------------------
<br><br>
Dear {{ $userName }},
<br><br>
Your project proposal has been permanently deleted, including any uploaded files.
The proposal had not started a workflow and had been inactive for at least four weeks.
<br><br>
OVERVIEW:
<br><br>
<b>Proposal:</b> {{ $proposalName }}
<br><br>
<b>Principal investigator:</b> {{ $userName }}
<br><br>
<b>Created:</b> {{ $createdDate }}
<br><br>
<b>ProposalID:</b> {{ $proposalId }}
<br><br>
<a href="{{ url('/projectproposals/my') }}">View My proposals</a>
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
