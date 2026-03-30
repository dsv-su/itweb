<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TravelRequest</title>
    <link rel="stylesheet" href="{{ public_path() .'/tr/pdf.css' }}" type="text/css">
</head>
<body>
@php
    $createdDate = filled($tr->created ?? null)
        ? \Carbon\Carbon::createFromTimestamp($tr->created)->toDateString()
        : null;

    $departureDate = filled($tr->departure ?? null)
        ? \Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString()
        : null;

    $returnDate = filled($tr->return ?? null)
        ? \Carbon\Carbon::createFromTimestamp($tr->return)->toDateString()
        : null;

    $decisionDate = filled($tr->updated_at ?? null)
        ? \Carbon\Carbon::parse($tr->updated_at)->format('Y-m-d')
        : null;

    $isRejected = in_array($tr->state ?? null, ['manager_denied', 'head_denied', 'fo_denied'], true);

    $projectId = filled($tr->project ?? null) ? $tr->project : __('NN');
    $paperAccepted = ($tr->paper ?? false) ? __('Yes') : __('No');

    $num = static fn ($value): float => (float) ($value ?? 0);

    $flight = $num($tr->flight ?? 0);
    $hotel = $num($tr->hotel ?? 0);
    $conference = $num($tr->conference ?? 0);
    $other = $num($tr->other_costs ?? 0);
    $daily = $num($tr->daily ?? 0);

    $days = (int) ($tr->days ?? 1);
    $dailyTotal = $daily * $days;

    $subTotal = $num($tr->total ?? 0);
    $vat = $subTotal * 0.25;
    $grandTotal = $subTotal;

    $commentOrFallback = static fn ($comment): string => filled($comment) ? (string) $comment : (string) __('No comments');

    $managerComment = null;
    if (filled($tr->manager_comment_id ?? null)) {
        $managerComment = \App\Models\ManagerComment::query()->whereKey($tr->manager_comment_id)->value('comment');
    }

    $headComment = null;
    if (filled($tr->head_comment_id ?? null)) {
        $headComment = \App\Models\HeadComment::query()->whereKey($tr->head_comment_id)->value('comment');
    }

    $foComment = null;
    if (filled($tr->fo_comment_id ?? null)) {
        $foComment = \App\Models\FoComment::query()->whereKey($tr->fo_comment_id)->value('comment');
    }
@endphp
<header>
    <div class="headerSection">
        <div class="logoAndName">
            <img
                src="{{ public_path('/images/su_cp.png') }}"
                alt="{{ __('Stockholms University') }}"
                width="100"
            />
        </div>
    </div>
    <p style="margin-top:.8cm; font-size:12pt;">
        {{ __('Department of Computer and Systems Sciences') }}
    </p>
</header>
<div class="container">
    <status>
        <table>
            <thead style="border: 1px solid;">
                <tr>
                    <th colspan="2" style="border: 1px solid; padding-right: 10px;">
                        <h3 style="margin: 10px;">{{ $isRejected ? __("Rejected Travelrequest") : __("Travelrequest") }}</h3>
                    </th>

                </tr>
                <tr>
                    <th style="border: 1px solid; padding: 5px;">{{__("Name")}}</th>
                    <th style="padding: 5px;">{{__("Created Date")}}</th>
                </tr>
            </thead>
            <tbody style="border: 1px solid;">
                <tr>
                    <td style="border: 1px solid; padding: 5px;">
                        {{$user->name}}
                    </td>
                    <td style="padding: 5px;">
                        {{ $createdDate ?? __('NN') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </status>
</div>
<br><br>
<req>
    <table>
        <thead>
            <tr>
                <th>{{__("ProjectID")}}</th>
                <th>{{__("Project leader")}}</th>
                <th>{{__("Unit Head")}}</th>
                <th>{{ $isRejected ? __('Rejected Date') : __('Approved date') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $projectId }}</td>
                <td>{{ $manager->name ?? '' }}</td>
                <td>{{ $head->name ?? '' }}</td>
                <td>{{ $decisionDate ?? __('NN') }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>{{__("Country")}}</th>
                <th>{{__("Daily subsistence allowances")}}</th>
                <th>{{__("Departure date")}}</th>
                <th>{{__("Return date")}}</th>
                <th>{{__("Total days")}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $tr->country ?? '' }}</td>
                <td>{{ $daily }} SEK</td>
                <td>{{ $departureDate ?? __('NN') }}</td>
                <td>{{ $returnDate ?? __('NN') }}</td>
                <td>{{ $days }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>{{__("Purpose")}}</th>
                <th>{{__("Paper accepted")}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $tr->purpose ?? '' }}</td>
                <td>{{ $paperAccepted }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <b>{{__("Comments from")}}:</b>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>{{__("Project leader")}}</th>
                <th>{{__("Unit Head")}}</th>
                <th>{{__("Financial Officer")}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $commentOrFallback($managerComment) }}</td>
                <td>{{ $commentOrFallback($headComment) }}</td>
                <td>{{ $commentOrFallback($foComment) }}</td>
            </tr>
        </tbody>
    </table>
</req>
<br><br>
<main>
    <table>
        <thead>
            <tr>
                <th>{{__("Expenses")}}</th>
                <th>SEK</th>
                <th>{{__("Amount")}}</th>
                <th>{{__("Total")}}</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <b>{{__("Flight")}}</b>
            </td>
            <td>{{ $flight }}</td>
            <td>{{ $flight > 0 ? 1 : 0 }}</td>
            <td>{{ $flight }}</td>
        </tr>
        <tr>
            <td>
                <b>{{__("Hotel")}}</b>
            </td>
            <td>{{ $hotel }}</td>
            <td>{{ $days }} ({{ __('days') }})</td>
            <td>{{ $hotel }}</td>
        </tr>
        <tr>
            <td>
                <b>{{__("Conference")}}</b>
            </td>
            <td>{{ $conference }}</td>
            <td>{{ $conference > 0 ? 1 : 0 }}</td>
            <td>{{ $conference }}</td>
        </tr>
        <tr>
            <td>
                <b>{{__("Other costs")}}</b>
            </td>
            <td>{{ $other }}</td>
            <td>{{ $other > 0 ? 1 : 0 }}</td>
            <td>{{ $other }}</td>
        </tr>
        <tr>
            <td>
                <b>{{__("Daily subsistence allowances")}}</b>
            </td>
            <td>{{ $daily }}</td>
            <td>{{ $days > 0 ? $days . ' ' . __('days') : 0 }}</td>
            <td>{{ $dailyTotal }}</td>
        </tr>
        </tbody>
    </table>
    <br><br>
    <table >
        <tr>
            <th>
                {{__("Subtotal")}}
            </th>
            <td>{{ $subTotal }}</td>
        </tr>
        <tr>
            <th>
                {{__("VAT")}} 25%
            </th>
            <td>{{ $vat }}</td>
        </tr>
        <tr>
            <th>
                {{__("Total")}}
            </th>
            <td><b>{{ $grandTotal }} SEK</b></td>
        </tr>
    </table>
</main>
</body>
</html>
