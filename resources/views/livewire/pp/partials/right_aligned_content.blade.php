@php
    $state = (string) ($proposal->dashboard?->state ?? '');

    $filesCount = count($proposal->files ?? []);
    $needsUpload = in_array($state, ['submitted', 'head_approved', 'fo_approved', 'final_approved'], true) && $filesCount <= 1;

    $isDenied = in_array($state, ['head_denied', 'vice_denied', 'fo_denied', 'final_denied', 'denied'], true);

    $uhApproved = in_array($state, ['head_approved', 'fo_returned', 'fo_approved', 'final_returned', 'final_approved', 'sent', 'granted'], true);
    $uhReturned = ($state === 'head_returned');
    $uhProcessing = ($state === 'complete' && $filesCount > 1);

    $ecoApproved = in_array($state, ['fo_approved', 'final_returned', 'final_approved', 'sent', 'granted'], true);
    $ecoReturned = ($state === 'fo_returned');
    $ecoPending  = ($state === 'head_returned');
    $ecoProcessing = ($state === 'head_approved');

    $finalApproved = in_array($state, ['final_approved', 'sent', 'granted'], true);
    $finalReturned = ($state === 'final_returned');
    $finalPending  = ($state === 'head_returned');
    $finalProcessing = ($state === 'fo_approved');

    $isSent = in_array($state, ['sent', 'granted'], true);
    $isGranted = ($state === 'granted');
@endphp

@php
    // Badge styles (compact + consistent)
    $badgeBase = 'inline-flex items-center rounded px-1.5 py-0.5 text-[0.70rem] font-semibold border whitespace-nowrap';

    $badge = function (string $kind) use ($badgeBase) {
        return match ($kind) {
            'ok'   => $badgeBase . ' bg-green-100 text-green-900 border-green-400 dark:bg-gray-700 dark:text-green-300',
            'bad'  => $badgeBase . ' bg-red-100 text-red-900 border-red-400 dark:bg-red-700 dark:text-red-200',
            'warn' => $badgeBase . ' bg-yellow-100 text-yellow-900 border-yellow-400 dark:bg-yellow-700 dark:text-yellow-100',
            'info' => $badgeBase . ' bg-blue-100 text-blue-900 border-blue-500 dark:bg-blue-700 dark:text-blue-100',
            'muted'=> $badgeBase . ' bg-gray-100 text-gray-900 border-gray-500 dark:bg-gray-700 dark:text-gray-200',
            default => $badgeBase . ' bg-gray-100 text-gray-900 border-gray-500 dark:bg-gray-700 dark:text-gray-200',
        };
    };
@endphp

    <!-- Right aligned content (compact) -->
<div class="mt-3 md:mt-0 w-full md:w-56 lg:w-64 md:shrink-0 md:text-right">
    <dl class="flex flex-col items-end gap-1.5 text-[0.75rem] leading-snug text-gray-700 dark:text-neutral-300">

        @if($needsUpload)
            <div class="flex items-baseline gap-2">
                <dt class="font-semibold">Upload files</dt>
                <dd><span class="{{ $badge('warn') }}">Waiting</span></dd>
            </div>
        @endif

        {{-- Unit head label --}}
        @php
            $unitHeads = is_array($proposal['pp']['unit_head'] ?? null) ? count($proposal['pp']['unit_head']) : 0;
            $uhLabel = $unitHeads > 1 ? "Unit heads ($unitHeads)" : 'Unit head';
        @endphp
        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">{{ $uhLabel }}</dt>
            <dd>
                @if($uhApproved)
                    <span class="{{ $badge('ok') }}">Approved</span>
                @elseif($isDenied)
                    <span class="{{ $badge('bad') }}">Denied</span>
                @elseif($uhReturned)
                    <span class="{{ $badge('warn') }}">Returned</span>
                @elseif($uhProcessing)
                    <span class="{{ $badge('info') }}">Processing</span>
                @else
                    <span class="{{ $badge('muted') }}">Waiting</span>
                @endif
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Economy</dt>
            <dd>
                @if($ecoApproved)
                    <span class="{{ $badge('ok') }}">Approved</span>
                @elseif($isDenied)
                    <span class="{{ $badge('bad') }}">Denied</span>
                @elseif($ecoReturned)
                    <span class="{{ $badge('warn') }}">Returned</span>
                @elseif($ecoPending)
                    <span class="{{ $badge('muted') }}">Pending</span>
                @elseif($ecoProcessing)
                    <span class="{{ $badge('info') }}">Processing</span>
                @else
                    <span class="{{ $badge('muted') }}">Waiting</span>
                @endif
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Final approval</dt>
            <dd>
                @if($finalApproved)
                    <span class="{{ $badge('ok') }}">Approved</span>
                @elseif($isDenied)
                    <span class="{{ $badge('bad') }}">Denied</span>
                @elseif($finalReturned)
                    <span class="{{ $badge('warn') }}">Returned</span>
                @elseif($finalPending)
                    <span class="{{ $badge('muted') }}">Pending</span>
                @elseif($finalProcessing)
                    <span class="{{ $badge('info') }}">Processing</span>
                @else
                    <span class="{{ $badge('muted') }}">Waiting</span>
                @endif
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Final submission</dt>
            <dd>
                @if($isSent)
                    <span class="{{ $badge('ok') }}">Sent</span>
                @elseif($state === 'denied')
                    <span class="{{ $badge('bad') }}">Denied</span>
                @else
                    <span class="{{ $badge('muted') }}">Not sent</span>
                @endif
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Decision expected</dt>
            <dd class="text-gray-900 dark:text-neutral-200">
                {{ $proposal->pp['decision_exp'] ?? 'N/A' }}
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Start date expected</dt>
            <dd class="text-gray-900 dark:text-neutral-200">
                {{ $proposal->pp['start_date'] ?? 'N/A' }}
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Funding granted</dt>
            <dd>
                @if($isGranted)
                    <span class="{{ $badge('ok') }}">Yes</span>
                @elseif($state === 'denied')
                    <span class="{{ $badge('bad') }}">Denied</span>
                @else
                    <span class="{{ $badge('muted') }}">Not reported</span>
                @endif
            </dd>
        </div>

        <div class="flex items-baseline gap-2">
            <dt class="font-semibold">Uploaded files</dt>
            <dd>
                <span class="{{ $filesCount > 0 ? $badge('info') : $badge('muted') }}">
                    {{ $filesCount }}
                </span>
            </dd>
        </div>
    </dl>
</div>

