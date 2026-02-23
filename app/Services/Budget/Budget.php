<?php

namespace App\Services\Budget;

use App\Models\DsvBudget;
use App\Models\ProjectProposal;

class Budget
{
    protected $budget, $proposal, $research_area;

    public function __construct(ProjectProposal $proposal)
    {
        $this->budget = DsvBudget::find(1);
        $this->proposal = $proposal;
    }

    public function preapproved_increment($researchAreaToUpdate)
    {
        $this->research_area = $this->budget->research_area;
        // Ensure decoding was successful
        if (!is_array($this->research_area)) {
            $research_area = [];
        }
        // Ensure the research area exists
        if (!isset($this->research_area[$researchAreaToUpdate])) {
            $this->research_area[$researchAreaToUpdate] = ['preapproved' => 0];
        }
        // Increment the 'preapproved' count for the specific research area
        $this->research_area[$researchAreaToUpdate]['preapproved']++;

        //Update research_area
        $this->budget->research_area = $this->research_area;

        //Update total
        $this->budget->preapproved_total++;

        // Save the updated JSON
        $this->budget->save();
    }

    public function budget_increment($researchAreaToUpdate)
    {
        $this->research_area = $this->budget->research_area;
        // Ensure decoding was successful
        if (!is_array($this->research_area)) {
            $research_area = [];
        }
        // Ensure the research area exists
        if (!isset($this->research_area[$researchAreaToUpdate])) {
            $this->research_area[$researchAreaToUpdate] = ['budget_sek' => 0];
            $this->research_area[$researchAreaToUpdate] = ['budget_eur' => 0];
            $this->research_area[$researchAreaToUpdate] = ['budget_usd' => 0];
        }
        // Increase the 'budget' for the specific research area
        switch($this->proposal->pp['currency']) {
            case 'sek':
                $this->research_area[$researchAreaToUpdate]['budget_sek'] += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
            case 'eur':
                $this->research_area[$researchAreaToUpdate]['budget_eur'] += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
            case 'usd':
                $this->research_area[$researchAreaToUpdate]['budget_usd'] += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
        }

        //Update research_area
        $this->budget->research_area = $this->research_area;

        //Update dsv total
        switch($this->proposal->pp['currency']) {
            case 'sek':
                $this->budget->budget_dsv_total_sek += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
            case 'eur':
                $this->budget->budget_dsv_total_eur += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
            case 'usd':
                $this->budget->budget_dsv_total_usd += $this->proposal->pp['budget_dsv'] ?? 0;
                break;
        }

        //Update project total
        switch($this->proposal->pp['currency']) {
            case 'sek':
                $this->budget->budget_project_total_sek += $this->proposal->pp['budget_project'] ?? 0;
                break;
            case 'eur':
                $this->budget->budget_project_total_eur += $this->proposal->pp['budget_project'] ?? 0;
                break;
            case 'usd':
                $this->budget->budget_project_total_usd += $this->proposal->pp['budget_project'] ?? 0;
                break;
        }

        // Save the updated JSON
        $this->budget->save();
    }

    public function phd_increment($researchAreaToUpdate)
    {
        $this->research_area = $this->budget->research_area;
        // Ensure decoding was successful
        if (!is_array($this->research_area)) {
            $research_area = [];
        }
        // Ensure the research area exists
        if (!isset($this->research_area[$researchAreaToUpdate])) {
            $this->research_area[$researchAreaToUpdate] = ['phd' => 0];
        }
        // Increase the 'phd-budget' for the specific research area
        $this->research_area[$researchAreaToUpdate]['phd'] += $this->proposal->pp['budget_phd'] ?? 0;


        //Update research_area
        $this->budget->research_area = $this->research_area;

        //Update total
        $this->budget->phd_total += $this->proposal->pp['budget_phd'] ?? 0;

        // Save the updated JSON
        $this->budget->save();
    }

    public function cost_increment($researchAreaToUpdate)
    {
        $this->research_area = $this->budget->research_area;
        // Ensure decoding was successful
        if (!is_array($this->research_area)) {
            $research_area = [];
        }
        // Ensure the research area exists
        if (!isset($this->research_area[$researchAreaToUpdate])) {
            $this->research_area[$researchAreaToUpdate] = ['cost_sek' => 0];
            $this->research_area[$researchAreaToUpdate] = ['cost_eur' => 0];
            $this->research_area[$researchAreaToUpdate] = ['cost_usd' => 0];
        }
        // Increase the 'cost' for the specific research area
        switch($this->proposal->pp['currency']) {
            case 'sek':
                $this->research_area[$researchAreaToUpdate]['cost_sek'] += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
            case 'eur':
                $this->research_area[$researchAreaToUpdate]['cost_eur'] += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
            case 'usd':
                $this->research_area[$researchAreaToUpdate]['cost_usd'] += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
        }

        //Update research_area
        $this->budget->research_area = $this->research_area;

        //Update total
        switch($this->proposal->pp['currency']) {
            case 'sek':
                $this->budget->cost_total_sek += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
            case 'eur':
                $this->budget->cost_total_eur += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
            case 'usd':
                $this->budget->cost_total_usd += $this->proposal->pp['cofinancing_needed'] ?? 0;
                break;
        }

        // Save the updated JSON
        $this->budget->save();
    }
}
