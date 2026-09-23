<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project proposal statistics — {{ $stats['month'] }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;color:#1e293b;font-family:Arial,Helvetica,sans-serif;">
<div style="display:none;max-height:0;overflow:hidden;">{{ $stats['month'] }}: {{ $stats['total'] }} proposals, {{ $stats['granted'] }} granted. Your monthly DSV summary.</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f1f5f9;">
    <tr><td align="center" style="padding:24px 12px;">
        <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="width:100%;max-width:640px;background-color:#ffffff;border:1px solid #e2e8f0;border-radius:12px;">
            <tr><td style="padding:32px 28px;background-color:#002f5f;border-radius:12px 12px 0 0;color:#ffffff;">
                <p style="margin:0 0 12px;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#bfdbfe;">DSV · Project proposals</p>
                <h1 style="margin:0;font-size:28px;line-height:1.25;">Your monthly summary</h1>
                <p style="margin:12px 0 0;font-size:18px;color:#dbeafe;">{{ $stats['month'] }}</p>
            </td></tr>
            <tr><td style="padding:28px;">
                <p style="margin:0 0 12px;line-height:1.6;">Hello {{ $recipient['name'] ?? 'colleague' }},</p>
                <p style="margin:0 0 24px;line-height:1.6;color:#475569;">Here is the summary for proposals with submission deadlines from <strong>{{ $stats['period'] }}</strong> that are currently registered as sent or granted.</p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="text-align:center;background-color:#eff6ff;border-radius:8px;">
                    <tr>
                        @foreach (['total' => 'Sent or granted', 'awaiting' => 'Awaiting outcome', 'granted' => 'Granted'] as $key => $label)
                            <td width="33%" style="padding:20px 8px;vertical-align:top;">
                                <p style="margin:0 0 6px;font-size:30px;font-weight:bold;color:#1d4ed8;">{{ number_format($stats[$key]) }}</p>
                                <p style="margin:0;font-size:12px;line-height:1.5;color:#475569;">{{ $label }}</p>
                            </td>
                        @endforeach
                    </tr>
                </table>
                @if ($stats['total'] === 0)
                    <p style="margin:24px 0;line-height:1.6;">There were no sent or granted proposals with submission deadlines in this month.</p>
                @else
                    <h2 style="margin:28px 0 12px;font-size:18px;color:#0f172a;">DSV budget overview</h2>
                    @foreach ($stats['budgets'] as $currency => $amounts)
                        <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:16px;border:1px solid #e2e8f0;border-collapse:collapse;font-size:14px;">
                            <tr><th colspan="2" scope="colgroup" align="left" style="padding:12px;background-color:#f8fafc;color:#002f5f;">{{ $currency }}</th></tr>
                            @foreach (['requested' => 'DSV budget · all sent/granted proposals', 'granted' => 'DSV budget · granted proposals only', 'cofinancing' => 'Co-financing needed · all sent/granted'] as $key => $label)
                                <tr>
                                    <th scope="row" align="left" style="padding:12px;border-top:1px solid #e2e8f0;font-weight:normal;line-height:1.5;">{{ $label }}</th>
                                    <td align="right" style="padding:12px;border-top:1px solid #e2e8f0;white-space:nowrap;font-weight:bold;">{{ number_format($amounts[$key], 2, '.', ' ') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                    <p style="font-size:14px;line-height:1.6;color:#475569;">Planned PhD years: <strong>{{ number_format($stats['phd_years'], 2) }}</strong>. Amounts are shown in their original currencies, without conversion.</p>
                    @foreach (['research_subjects' => 'Research subjects', 'funding_organizations' => 'Funding organizations'] as $key => $heading)
                        <h2 style="margin:28px 0 12px;font-size:18px;color:#0f172a;">{{ $heading }}</h2>
                        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
                            <tr>
                                <th scope="col" align="left" style="padding:10px 0;border-bottom:2px solid #e2e8f0;color:#64748b;">{{ $heading }}</th>
                                <th scope="col" align="right" style="padding:10px 0;border-bottom:2px solid #e2e8f0;color:#64748b;">Proposals</th>
                            </tr>
                            @foreach ($stats[$key] as $label => $count)
                                <tr>
                                    <th scope="row" align="left" style="padding:12px 0;border-bottom:1px solid #e2e8f0;font-weight:normal;">{{ $label }}</th>
                                    <td align="right" style="padding:12px 0;border-bottom:1px solid #e2e8f0;font-weight:bold;">{{ $count }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                @endif
                <p style="margin:28px 0 0;padding:16px;background-color:#f8fafc;font-size:12px;line-height:1.6;color:#64748b;">These figures follow the proposal statistics convention: the reporting month is determined by submission deadline, with status and budget values at the time this summary is generated. They do not count status changes during the month. Drafts and other statuses are excluded.</p>
            </td></tr>
            <tr><td style="padding:20px 28px;border-top:1px solid #e2e8f0;font-size:12px;line-height:1.6;color:#64748b;">
                You receive this email because you are listed under Monthly statistics in the Vice Head notification settings. Contact the Vice Head to update your subscription.<br>
                This is an automated email from ProjectProposal. Please do not reply.
            </td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
