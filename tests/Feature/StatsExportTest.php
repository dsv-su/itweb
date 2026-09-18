<?php

namespace Tests\Feature;

use App\Exports\StatsChartSheet;
use App\Exports\StatsExport;
use App\Models\DsvBudget;
use App\Services\Stats\OverviewCharts;
use App\Services\Stats\PrincipalInvestigatorCharts;
use App\Services\Stats\StatsChartFactory;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\BootProviders;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class StatsExportTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->beforeBootstrapping(BootProviders::class, function ($app) {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite.database', ':memory:');
            $app['config']->set('statamic.eloquent-driver.connection', 'sqlite');
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function test_workbook_contains_real_tables_numeric_values_and_literal_labels(): void
    {
        $chart = app(StatsChartFactory::class)->bar('test', ['=1+1', 'Zero'], 'Budget SEK', [1234.5, 0], 'blue');
        $grouped = app(PrincipalInvestigatorCharts::class)->grouped(['Alpha' => ['Alice' => 2], 'Beta' => ['Bob' => 3]]);
        $export = new class($chart, $grouped) implements WithMultipleSheets
        {
            public function __construct(private $chart, private $grouped) {}

            public function sheets(): array
            {
                return [
                    new StatsChartSheet('Budget SEK', $this->chart, 'Committed | 2026 | Overview', 1),
                    new StatsChartSheet('Investigators', $this->grouped, 'Committed | 2026 | Per unit', 2),
                ];
            }
        };
        $path = tempnam(sys_get_temp_dir(), 'stats-export-');
        try {
            file_put_contents($path, app(Excel::class)->raw($export, Excel::XLSX));
            Cell::setValueBinder(new DefaultValueBinder);
            $workbook = IOFactory::load($path);
            $sheet = $workbook->getSheet(0);
            $this->assertCount(1, $sheet->getTableCollection());
            $this->assertSame('A4:B6', $sheet->getTableCollection()[0]->getRange());
            $this->assertSame('B5', $sheet->getFreezePane());
            $this->assertSame('=1+1', $sheet->getCell('A5')->getValue());
            $this->assertSame(DataType::TYPE_STRING, $sheet->getCell('A5')->getDataType());
            $this->assertSame(1234.5, $sheet->getCell('B5')->getValue());
            $this->assertSame(0, $sheet->getCell('B6')->getValue());
            $this->assertSame([['Alpha', 'Alice', 2], ['Beta', 'Bob', 3]], $workbook->getSheet(1)->rangeToArray('A5:C6', null, false, false));
        } finally {
            unlink($path);
        }
    }

    public function test_overview_exports_only_charts_visible_on_each_tab(): void
    {
        $budget = new DsvBudget;
        $budget->research_area = ['Subject' => ['preapproved' => 2, 'budget_sek' => 42]];
        $budget->funding_org = ['Agency' => 2];
        $investigator = app(StatsChartFactory::class)->bar('pi', ['Alice'], 'Proposals', [2], 'blue');
        foreach ([false => 7, true => 7] as $granted => $expectedCount) {
            $export = new StatsExport([
                'chart' => app(OverviewCharts::class)->build($budget, (bool) $granted),
                'investigatorChart' => $investigator,
            ], (bool) $granted, 'overview', 2026);
            $sheets = $export->sheets();
            $this->assertCount($expectedCount, $sheets);
            $titles = array_map(fn ($sheet) => $sheet->title(), $sheets);
            $this->assertSame(! $granted, in_array('Funding agency', $titles));
            $this->assertSame(! $granted, in_array('PhD years', $titles));
        }
    }
}
