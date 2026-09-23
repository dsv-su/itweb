DSV · PROJECT PROPOSALS
Monthly summary — {{ $stats['month'] }}

Hello {{ $recipient['name'] ?? 'colleague' }},

Submission deadlines: {{ $stats['period'] }}

Sent or granted: {{ $stats['total'] }}
Awaiting outcome: {{ $stats['awaiting'] }}
Granted: {{ $stats['granted'] }}
Planned PhD years: {{ number_format($stats['phd_years'], 2) }}

@if ($stats['total'] === 0)
There were no sent or granted proposals with submission deadlines in this month.
@else
DSV BUDGET OVERVIEW
@foreach ($stats['budgets'] as $currency => $amounts)
{{ $currency }}
DSV budget (all sent/granted): {{ number_format($amounts['requested'], 2, '.', ' ') }}
DSV budget (granted only): {{ number_format($amounts['granted'], 2, '.', ' ') }}
Co-financing needed (all sent/granted): {{ number_format($amounts['cofinancing'], 2, '.', ' ') }}

@endforeach
Amounts remain in their original currencies, without conversion.

@foreach (['research_subjects' => 'RESEARCH SUBJECTS', 'funding_organizations' => 'FUNDING ORGANIZATIONS'] as $key => $heading)
{{ $heading }}
@foreach ($stats[$key] as $label => $count)
{{ $label }}: {{ $count }}
@endforeach

@endforeach
@endif
Figures follow the proposal statistics convention: submission deadline determines the reporting month; statuses and budget values are current when the summary is generated. These are not counts of status changes during the month. Drafts and other statuses are excluded.

You receive this email because you are listed under Monthly statistics in the Vice Head notification settings. Contact the Vice Head to update your subscription.
This is an automated email from ProjectProposal. Please do not reply.
