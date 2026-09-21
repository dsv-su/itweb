<?php

namespace App\Imports;

use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectsRefreshImport implements SkipsEmptyRows, ToCollection, WithHeadingRow, WithMultipleSheets
{
    public int $count = 0;

    public function sheets(): array
    {
        return [0 => $this];
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            throw ValidationException::withMessages(['file' => 'Det första kalkylbladet måste innehålla projektrader.']);
        }

        $projects = $rows->map(function ($row) {
            $data = [];
            foreach (['projekt' => 'project', 'projektbenamning' => 'description', 'projektledare' => 'projectleader', 'status' => 'status'] as $heading => $field) {
                $data[$field] = isset($row[$heading]) ? trim((string) $row[$heading]) : null;
            }

            return $data;
        })->all();

        $attributes = [];
        foreach ($projects as $index => $project) {
            foreach (['project' => 'projektnummer', 'description' => 'projektbenämning', 'projectleader' => 'projektledare', 'status' => 'status'] as $field => $label) {
                $attributes["rows.{$index}.{$field}"] = 'projektpost '.($index + 1).' '.$label;
            }
        }

        $validator = Validator::make(['rows' => $projects], [
            'rows.*.project' => ['required', 'string', 'max:255', 'distinct'],
            'rows.*.description' => ['required', 'string', 'max:255'],
            'rows.*.projectleader' => ['required', 'string', 'max:255'],
            'rows.*.status' => ['required', 'string', 'max:255'],
        ], [], $attributes);
        if ($validator->fails()) {
            throw ValidationException::withMessages([
                'file' => $validator->errors()->all(),
            ]);
        }

        DB::transaction(function () use ($projects) {
            foreach ($projects as $data) {
                Project::updateOrCreate(['project' => $data['project']], $data);
            }
        });

        $this->count = count($projects);
    }
}
