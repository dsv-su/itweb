<?php

namespace App\Services\Stats;

use IcehouseVentures\LaravelChartjs\Builder;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

/** Shared bar sizing and dataset styling; callers add chart-specific axis options */
class StatsChartFactory
{
    // Labels and data must have matching positions; the name identifies the canvas
    public function bar(string $name, array $labels, string $label, array $data, string $color): Builder
    {
        return Chartjs::build()
            ->name($name)
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    'label' => $label,
                    'backgroundColor' => $color,
                    'borderWidth' => 1,
                    'data' => $data,
                    'categoryPercentage' => 0.6,
                    'barPercentage' => 0.6,
                    'yAxisID' => 'y-left',
                ],
            ]);
    }
}
