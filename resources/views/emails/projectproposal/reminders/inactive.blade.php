<br>
To: User {{ $userName }}
<br>
Subject: Your project proposal, <i><strong>{{ $proposalName }}</strong></i>, is inactive.
<br>
------------------------------------------------------------
<br><br>
Dear {{ $userName }},
<br><br>
You have a saved project proposal that is at least four weeks old and has not been updated for at least four weeks.
It has not started an approval workflow. If you no longer need this proposal, you can delete it.
<br><br>
<strong>How to delete your proposal:</strong>
<ol>
    <li>Open <a href="{{ route('pp.show', 'my') }}">My proposals</a></li>
    <li>Find <strong>{{ $proposalName }}</strong> and select <strong>View details</strong>.</li>
    <li>Select <strong>Delete</strong>, then confirm the deletion when prompted.</li>
</ol>
<strong>Deletion is permanent:</strong> the proposal and its uploaded files will be removed. You will receive a confirmation email after deletion.
<br><br>
Only you as the principal investigator or Helpdesk can delete this proposal. The Delete button is available only while the proposal has no workflow and has been inactive for at least four weeks.
<br><br>
If you wish to keep working on the proposal, select <strong>Continue</strong> in View details and save your changes instead.
<br><br>
OVERVIEW:
<br><br>
<b>Proposal:</b> {{ $proposalName }}
<br><br>
<b>Created:</b> {{ $createdDate }}
<br><br>
<b>Last updated:</b> {{ $updatedDate }}
<br><br>
<b>ProposalID:</b> {{ $proposalId }}
<br><br>
------------------------------------------------------------
<br>
This is an automated email, please do not reply to this email.
