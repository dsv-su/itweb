<?php

namespace App\Http\Controllers;

use App\Services\Skatteverket;

class ProjectController extends Controller
{
    public function getCountry()
    {
        $skatteverket = new Skatteverket();
        $skatteverket->getCountry();
        $skatteverket->checkAllowance();
    }
}
