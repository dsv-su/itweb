<div lang="sv">
    <strong>Resebegäran</strong>
    <p>Hej {{ $recipient->name }},</p>
    <p>Detta är en påminnelse om att resebegäran <strong>{{ $dashboard->name }}</strong> väntar på din granskning.</p>
    <p>
        <b>Sökande:</b> {{ $user->name }}<br>
        <b>Begärans ID:</b> {{ $dashboard->request_id }}<br>
        <b>Land:</b> {{ $travelRequest?->country ?? 'Ej angivet' }}<br>
        <b>Total kostnad:</b> {{ isset($travelRequest->total) ? number_format($travelRequest->total, 0, ',', ' ').' SEK' : 'Ej angivet' }}
    </p>
    <p><b>Syfte:</b><br>{!! nl2br(e($travelRequest?->purpose ?? 'Ej angivet')) !!}</p>
    <p><a href="{{ route('travel-request-review', $dashboard->id) }}">Granska resebegäran</a></p>
    <p>Detta är ett automatiskt e-postmeddelande. Vänligen svara inte på detta meddelande.</p>
</div>
<hr>
<div lang="en">
    <strong>Travelrequest</strong>
    <p>Dear {{ $recipient->name }},</p>
    <p>This is a reminder that the travel request <strong>{{ $dashboard->name }}</strong> is awaiting your review.</p>
    <p>
        <b>Requester:</b> {{ $user->name }}<br>
        <b>Request ID:</b> {{ $dashboard->request_id }}<br>
        <b>Country:</b> {{ $travelRequest?->country ?? 'Not specified' }}<br>
        <b>Total cost:</b> {{ isset($travelRequest->total) ? number_format($travelRequest->total, 0, '.', ',').' SEK' : 'Not specified' }}
    </p>
    <p><b>Purpose:</b><br>{!! nl2br(e($travelRequest?->purpose ?? 'Not specified')) !!}</p>
    <p><a href="{{ route('travel-request-review', $dashboard->id) }}">Review the travel request</a></p>
    <p>This is an automated email, please do not reply to this email.</p>
</div>
