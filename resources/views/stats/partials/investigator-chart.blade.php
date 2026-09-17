@include('stats.partials.unit-chart', [
    'heading' => match (request('breakdown')) {
        'unit' => 'Proposals per principal investigator by unit',
        'research_area' => 'Proposals per principal investigator by research subject',
        default => 'Proposals per principal investigator',
    },
    'unitChart' => $investigatorChart,
    'badge' => request()->routeIs('pp.stats.committed') ? 'Sent' : 'Granted',
    'note' => (match (request('breakdown')) {
        'unit' => 'Investigators are grouped by the proposal’s unit heads. Proposals linked to multiple units appear once in each unit. ',
        'research_area' => 'Investigators are grouped by the proposal’s research subject. Missing subjects appear as “Unknown research subject”. ',
        default => '',
    }).(request()->routeIs('pp.stats.committed')
        ? 'Proposals sent to the funder, including those subsequently granted. Missing names appear as “Unknown principal investigator”.'
        : 'Proposals granted by the funder. Missing names appear as “Unknown principal investigator”.'),
])
