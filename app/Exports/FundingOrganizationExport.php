<?php

namespace App\Exports;

use App\Models\FundingOrganization;
use Maatwebsite\Excel\Concerns\FromCollection;

class FundingOrganizationExport implements FromCollection
{
    /*public function collection()
    {
        return FundingOrganization::all();
    }*/
    public function collection()
    {
        return FundingOrganization::query()
            ->select('name')
            ->get();
    }
}
