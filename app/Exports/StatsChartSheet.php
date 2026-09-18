<?php

namespace App\Exports;

use IcehouseVentures\LaravelChartjs\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

class StatsChartSheet extends DefaultValueBinder implements FromArray, ShouldAutoSize, WithCustomValueBinder, WithEvents, WithHeadings, WithStrictNullComparison, WithTitle
{
    public function __construct(private string $name, private Builder $chart, private string $context, private int $index) {}

    public function title(): string
    {
        return $this->name;
    }

    public function headings(): array
    {
        if ($this->grouped()) {
            return ['Group', 'Principal investigator', 'Value'];
        }

        $headings = ['Category'];
        foreach ($this->chart->get('datasets') as $dataset) {
            $label = (string) ($dataset['label'] ?? 'Value');
            $heading = $label;
            $suffix = 2;
            while (in_array(strtolower($heading), array_map('strtolower', $headings), true)) {
                $heading = $label.' ('.$suffix++.')';
            }
            $headings[] = $heading;
        }

        return $headings;
    }

    public function array(): array
    {
        $datasets = $this->chart->get('datasets');
        $rows = [];
        foreach ($this->chart->get('labels') as $index => $label) {
            if ($this->grouped()) {
                $value = null;
                foreach ($datasets as $dataset) {
                    if (($dataset['data'][$index] ?? null) !== null) {
                        $value = $dataset['data'][$index];
                        break;
                    }
                }
                $rows[] = [...$label, $value];
            } else {
                $rows[] = [$label, ...array_map(fn ($dataset) => $dataset['data'][$index] ?? null, $datasets)];
            }
        }

        return $rows;
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        // Preserve names literally, including numeric names and formula-like text.
        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            $sheet->insertNewRowBefore(1, 3);
            $sheet->setCellValueExplicit('A1', $this->name, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A2', $this->context, DataType::TYPE_STRING);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->freezePane('B5');
            $lastColumn = Coordinate::stringFromColumnIndex(count($this->headings()));
            $lastRow = max(5, count($this->array()) + 4);
            $table = new Table("A4:{$lastColumn}{$lastRow}", 'StatsTable'.$this->index);
            $table->setStyle((new TableStyle)->setTheme(TableStyle::TABLE_STYLE_MEDIUM2)->setShowRowStripes(true));
            $sheet->addTable($table);
            $firstNumberColumn = $this->grouped() ? 'C' : 'B';
            if (count($this->headings()) > 1) {
                $sheet->getStyle("{$firstNumberColumn}5:{$lastColumn}{$lastRow}")->getNumberFormat()->setFormatCode('#,##0.##');
            }
            // Metadata should not make the category column excessively wide.
            $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(42);
            $sheet->getStyle("A5:A{$lastRow}")->getAlignment()->setWrapText(true);
        }];
    }

    private function grouped(): bool
    {
        return is_array($this->chart->get('labels')[0] ?? null);
    }
}
