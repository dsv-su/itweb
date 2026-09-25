@extends('layouts.app')
@section('content')
    @nocache('dsvheader')
    @include('pp.partials.header')
    @include('pp.partials.breadcrumb')
    @include('pp.partials.flashmessage')
    @if($errors->hasAny(['state', 'resume_stage', 'workflow_id']))
        <div role="alert" class="mx-auto mt-4 max-w-[85rem] px-6 text-sm text-red-600 dark:text-red-400">{{ $errors->first('state') ?: ($errors->first('resume_stage') ?: $errors->first('workflow_id')) }}</div>
    @endif

    <section class="mx-auto max-w-[85rem] px-4 py-8 sm:px-6 lg:px-8" aria-labelledby="proposals-heading">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">Administration</p>
                <h1 id="proposals-heading" class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-neutral-100">Proposals</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Find proposals and review their details and progress.</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800">
            <form method="GET" action="{{ route('admin.pp.index') }}" role="search" class="border-b border-gray-200 p-4 sm:p-6 dark:border-neutral-700">
                <label for="proposal-search" class="mb-2 block text-sm font-medium text-gray-900 dark:text-neutral-200">Search proposals</label>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-3 size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <circle cx="10.5" cy="10.5" r="6.5" />
                            <path stroke-linecap="round" d="m16 16 4.5 4.5" />
                        </svg>
                        <input id="proposal-search" type="search" name="search" value="{{ $search }}" maxlength="200" placeholder="Search by name, proposal ID, principal investigator, submitter, research subject or funder" aria-describedby="proposal-search-help" class="block w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder:text-neutral-500">
                    </div>
                    <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Search</button>
                    @if($search !== '')
                        <a href="{{ route('admin.pp.index') }}" class="rounded-lg px-3 py-2.5 text-center text-sm font-medium text-gray-600 hover:text-gray-900 focus-visible:outline-2 focus-visible:outline-blue-600 dark:text-neutral-300 dark:hover:text-white">Clear search</a>
                    @endif
                </div>
                <p id="proposal-search-help" class="mt-2 text-xs text-gray-500 dark:text-neutral-400">Search all proposals. Results are shown newest first.</p>
                @error('search')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </form>

            <div class="flex flex-wrap items-center gap-2 px-4 py-4 sm:px-6">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-neutral-200">{{ $search !== '' ? 'Search results' : 'All proposals' }}</h2>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium tabular-nums text-gray-700 dark:bg-neutral-700 dark:text-neutral-200">{{ number_format($proposals->total()) }}</span>
                @if($search !== '')
                    <span class="break-all text-sm text-gray-500 dark:text-neutral-400">for &ldquo;{{ $search }}&rdquo;</span>
                @endif
            </div>

    @php
        $workflowIds = $proposals->pluck('dashboard.workflow_id')->filter()->unique();
        $proposalWorkflows = config('workflows.stored_workflow_model', \Workflow\Models\StoredWorkflow::class)::query()
            ->with('activeWorkflow')
            ->whereIn('id', $workflowIds)
            ->get()
            ->keyBy('id');
    @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-neutral-700">
                    <caption class="sr-only">Proposals with submitter, research subject, funder, status, workflow status, file count and creation date.</caption>
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-neutral-900/50 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left font-semibold">Proposal / Submitter</th>
                            <th scope="col" class="px-6 py-3 text-left font-semibold">Research subject / Funder</th>
                            <th scope="col" class="px-6 py-3 text-left font-semibold">Status</th>
                            <th scope="col" class="px-4 py-3 text-center font-semibold">Workflow</th>
                            <th scope="col" class="px-4 py-3 text-center font-semibold">Files</th>
                            <th scope="col" class="px-6 py-3 text-left font-semibold">Created</th>
                            <th scope="col" class="px-6 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                        @forelse($proposals as $proposal)
                            <tr class="align-top hover:bg-gray-50/80 dark:hover:bg-neutral-700/30">
                                <th scope="row" class="min-w-64 max-w-md px-6 py-5 text-left font-normal">
                                    <span class="block break-words font-semibold text-gray-900 dark:text-neutral-100">{{ $proposal->name }}</span>
                                    <span class="mt-1 block text-gray-500 dark:text-neutral-400">{{ $proposal->submitter?->name ?? 'Unknown submitter' }}</span>
                                </th>
                                <td class="min-w-56 max-w-xs px-6 py-5">
                                    <span class="block break-words text-gray-800 dark:text-neutral-200">{{ $proposal->pp['research_area'] ?? 'Not specified' }}</span>
                                    <span class="mt-1 block break-words text-gray-500 dark:text-neutral-400">{{ $proposal->pp['funding_organization'] ?? 'Not specified' }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    @if($proposal->dashboard)
                                        <span class="inline-flex whitespace-nowrap rounded border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-200">{{ $proposalStates[(string) $proposal->dashboard->state] ?? \Illuminate\Support\Str::headline((string) $proposal->dashboard->state) }}</span>
                                    @else
                                        <span class="text-gray-500 dark:text-neutral-400">Unavailable</span>
                                    @endif
                                </td>
                                <td class="px-4 py-5 text-center">
                                    @php
                                    $storedWorkflow = $proposalWorkflows->get($proposal->dashboard?->workflow_id);
                                    $activeWorkflow = $storedWorkflow?->status instanceof \Workflow\States\WorkflowContinuedStatus
                                        ? ($storedWorkflow->activeWorkflow->first() ?? $storedWorkflow)
                                        : $storedWorkflow;
                                    $workflowRunning = $activeWorkflow !== null
                                        && ! ($activeWorkflow->status instanceof \Workflow\States\WorkflowCompletedStatus)
                                        && ! ($activeWorkflow->status instanceof \Workflow\States\WorkflowFailedStatus);
                                    $workflowLabel = $workflowRunning ? 'Workflow running' : 'Workflow not running';
                                    @endphp
                                    @include('pp.admin.partials.workflow-icon')
                                </td>
                                <td class="px-4 py-5 text-center tabular-nums text-gray-600 dark:text-neutral-400">{{ count($proposal->files ?? []) }}</td>
                                <td class="whitespace-nowrap px-6 py-5 text-gray-600 dark:text-neutral-400">{{ $proposal->created ? \Carbon\Carbon::createFromTimestamp($proposal->created)->toDateString() : '—' }}</td>
                                <td class="px-6 py-5 text-right">
                                    @if($proposal->dashboard)
                                        <details class="min-w-56 text-left">
                                            <summary class="cursor-pointer rounded-lg text-right font-medium text-blue-600 focus-visible:outline-2 focus-visible:outline-blue-600 dark:text-blue-400">Manage proposal</summary>
                                            <div class="mt-3 space-y-4 rounded-lg border border-gray-200 p-3 dark:border-neutral-600">
                                                <form method="POST" action="{{ route('admin.pp.state', $proposal->id) }}" onsubmit="return confirm('Set this state and end any active workflow? Reminders will be disabled. No approval notifications will be sent.')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label for="state-{{ $proposal->id }}" class="mb-2 block text-xs font-medium text-gray-700 dark:text-neutral-300">Proposal state</label>
                                                    <select id="state-{{ $proposal->id }}" name="state" required class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100">
                                                        <option value="" disabled @selected(!array_key_exists((string) $proposal->dashboard->state, $proposalStates))>Choose a state</option>
                                                        @foreach($proposalStates as $value => $label)
                                                            <option value="{{ $value }}" @selected((string) $proposal->dashboard->state === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">Setting a state ends the active workflow and disables reminders.</p>
                                                    <button type="submit" class="mt-3 w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Set state</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.pp.resume-workflow', $proposal->id) }}" class="border-t border-gray-200 pt-3 dark:border-neutral-600" onsubmit="return confirm('Resume this proposal at the selected review stage? Any current workflow will end. Reminders and review notifications will be enabled.')">
                                                    @csrf
                                                    <input type="hidden" name="workflow_id" value="{{ $proposal->dashboard->workflow_id }}">
                                                    <label for="resume-{{ $proposal->id }}" class="mb-2 block text-xs font-medium text-gray-700 dark:text-neutral-300">Resume workflow at</label>
                                                    <select id="resume-{{ $proposal->id }}" name="resume_stage" required class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100">
                                                        <option value="" disabled @selected(!array_key_exists((string) $proposal->dashboard->state, $resumeStages))>Choose a review stage</option>
                                                        @foreach($resumeStages as $value => $label)
                                                            <option value="{{ $value }}" @selected((string) $proposal->dashboard->state === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">Required files are checked before review continues. Reminders and review notifications are enabled.</p>
                                                    <button type="submit" class="mt-3 w-full rounded-lg border border-blue-300 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:border-blue-800 dark:text-blue-400 dark:hover:bg-blue-950">Resume workflow</button>
                                                </form>
                                                @if($proposal->dashboard->workflow_id)
                                                    <form method="POST" action="{{ route('admin.pp.end-workflow', $proposal->id) }}" class="border-t border-gray-200 pt-3 dark:border-neutral-600" onsubmit="return confirm('End this proposal workflow and disable reminders? The proposal state will stay unchanged.')">
                                                        @csrf
                                                        <button type="submit" class="w-full rounded-lg border border-red-300 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-950">End workflow</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-neutral-400">No workflow dashboard</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="font-semibold text-gray-900 dark:text-neutral-200">{{ $search !== '' ? 'No matching proposals' : 'No proposals yet' }}</p>
                                    <p class="mt-2 text-gray-500 dark:text-neutral-400">{{ $search !== '' ? 'Try a different name, proposal ID, principal investigator, submitter, research subject or funder.' : 'Proposals will appear here once they are submitted.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-4 py-4 sm:px-6 dark:border-neutral-700">
                @if($proposals->hasPages())
                    {{ $proposals->links() }}
                @else
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Showing {{ number_format($proposals->count()) }} of {{ number_format($proposals->total()) }} {{ \Illuminate\Support\Str::plural('proposal', $proposals->total()) }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
