@php
    $wf = Workflow\WorkflowStub::load($proposal->dashboard->workflow_id);
    $state = $wf->status();
    $bgcolor = 'bg-yellow-100';
    $textcolor = 'text-yellow-800';
@endphp
<span class="{{$bgcolor}} {{$textcolor}} text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
    {{ $state }}
</span>
