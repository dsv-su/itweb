<div style="margin: 16px 0; padding: 16px; border: 1px solid #fde68a; border-radius: 8px; background-color: #fffbeb; color: #1f2937;">
    <h2 style="margin: 0 0 8px; font-size: 16px;">Project Organization changes</h2>
    <p style="margin: 0 0 12px;">The vice updated the following fields during final review. These changes are included in your approved proposal.</p>
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr>
                <th scope="col" style="padding: 8px; border-bottom: 1px solid #fde68a;">Field</th>
                <th scope="col" style="padding: 8px; border-bottom: 1px solid #fde68a;">Before review</th>
                <th scope="col" style="padding: 8px; border-bottom: 1px solid #fde68a;">Approved value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($organizationChanges as $change)
                <tr>
                    <th scope="row" style="padding: 8px; vertical-align: top;">{{ $change['label'] }}</th>
                    <td style="padding: 8px; vertical-align: top; overflow-wrap: anywhere;">{{ $change['before'] !== '' ? $change['before'] : 'Not specified' }}</td>
                    <td style="padding: 8px; vertical-align: top; overflow-wrap: anywhere;">{{ $change['after'] !== '' ? $change['after'] : 'Not specified' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
