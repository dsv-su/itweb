<?php

namespace App\Imports;

use App\Models\FundingOrganization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FundingOrganizationImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new FundingOrganization([
            'name' => $row[0], // second column in the row
        ]);
    }
}
