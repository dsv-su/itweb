<?php

namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjectImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Project([
            'project'  => $row['projekt'],
            'description' => $row['projektbenamning'],
            'projectleader'    => $row['projektledare'],
            'status' => $row['status'],
        ]);
    }
}
