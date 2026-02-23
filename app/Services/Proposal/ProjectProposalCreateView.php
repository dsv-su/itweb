<?php

namespace App\Services\Proposal;

use Statamic\View\View;

class ProjectProposalCreateView
{
    public function build($template, $layout, $data)
    {
        return (new View)->template($template)->layout($layout)->with($data);
    }
}
