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
<header>
  <div class="headerSection">
    <div class="logoAndName">
        <img src="{{ public_path() . '/images/su_cp.png' }}" alt="Stockholms University" width="100" />
    </div>
  </div>
  <p style="margin-top:.8cm; font-size:12pt;">{{__("Department of Computer and Systems Sciences")}}</p>
</header>
<div class="container">
    <status>
        <table>
            <thead style="border: 1px solid;">
            <tr>
                <th colspan="2" style="border: 1px solid; padding-right: 10px;">
                    <h3 style="margin: 10px;">{{__("TRAVELREQUEST")}}</h3>
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
                    {{\Carbon\Carbon::createFromTimestamp($tr->created)->toDateString()}}
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
            @if(in_array($tr->state, ['manager_denied', 'head_denied', 'fo_denied']))
                <th>{{__("Rejected Date")}}</th>
            @else
                <th>{{__("Approved date")}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                @if($tr->project)
                    {{$tr->project}}
                @else
                    NN
                @endif
            </td>
            <td>
                {{$manager->name}}
            </td>
            <td>
                {{$head->name}}
            </td>
            <td>
                {{\Carbon\Carbon::parse($tr->updated_at)->format('Y-m-d')}}
            </td>
        </tr>
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
            <td>
                {{$tr->country}}
            </td>
            <td>
                {{$tr->daily}} SEK
            </td>
            <td>
                @if($tr->departure)
                    {{\Carbon\Carbon::createFromTimestamp($tr->departure)->toDateString()}}
                @else
                    NN
                @endif
            </td>
            <td>
                @if($tr->return)
                    {{\Carbon\Carbon::createFromTimestamp($tr->return)->toDateString()}}
                @else
                    NN
                @endif
            </td>
            <td>
                {{$tr->days}}
            </td>
        </tr>
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
            <td>
                {{$tr->purpose}}
            </td>
            <td>
                @if($tr->paper == 0 or $tr->paper == null)
                {{__("No")}}
                @else
                {{__("Yes")}}
                @endif
            </td>
        </tr>
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
            <td>
                @if($tr->manager_comment_id ?? false)
                {{\App\Models\ManagerComment::find($tr->manager_comment_id)->comment}}
                @else
                    {{__("No comments")}}
                @endif
            </td>
            <td>
                @if($tr->head_comment_id ?? false)
                    {{\App\Models\HeadComment::find($tr->head_comment_id)->comment}}
                @else
                    {{__("No comments")}}
                @endif
            </td>
            <td>
                @if($tr->fo_comment_id ?? false)
                    {{\App\Models\FoComment::find($tr->fo_comment_id)->comment}}
                @else
                    {{__("No comments")}}
                @endif
            </td>
        </tr>
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
        <td>
            @if($tr->flight == null or $tr->flight == 0)
                0
            @else
                {{$tr->flight}}
            @endif
        </td>
        <td>
            @if($tr->flight == null or $tr->flight == 0)
                0
            @else
                1
            @endif
        </td>
        <td>
            @if($tr->flight == null or $tr->flight == 0)
                0
            @else
                {{$tr->flight}}
            @endif
        </td>
      </tr>
      <tr>
        <td>
          <b>{{__("Hotel")}}</b>
        </td>
        <td>
            @if($tr->hotel == null or $tr->hotel == 0)
                0
            @else
                {{$tr->hotel}}
            @endif
        </td>
        <td>
            @if($tr->days)
                {{$tr->days}}
            @else
                0
            @endif
            ({{__("days")}})
        </td>
        <td>
            @if($tr->hotel == null or $tr->hotel == 0)
                0
            @else
                {{$tr->hotel}}
            @endif
        </td>
      </tr>
      <tr>
          <td>
              <b>{{__("Conference")}}</b>
          </td>
          <td>
              @if($tr->conference == null or $tr->conference == 0)
                  0
              @else
                  {{$tr->conference}}
              @endif
          </td>
          <td>
              @if($tr->conference == null or $tr->conference == 0)
                  0
              @else
                  1
              @endif
          </td>
          <td>
              @if($tr->conference == null or $tr->conference == 0)
                  0
              @else
                  {{$tr->conference}}
              @endif
          </td>
      </tr>
      <tr>
        <td>
          <b>{{__("Other costs")}}</b>
        </td>
        <td>
            @if($tr->other_costs == null or $tr->other_costs == 0)
                0
            @else
                {{$tr->other_costs}}
            @endif
        </td>
        <td>
            @if($tr->other_costs == null or $tr->other_costs == 0)
                0
            @else
                1
            @endif
        </td>
        <td>
            @if($tr->other_costs == null or $tr->other_costs == 0)
                0
            @else
                {{$tr->other_costs}}
            @endif
        </td>
      </tr>
      <tr>
          <td>
              <b>{{__("Daily subsistence allowances")}}</b>
          </td>
          <td>
              @if($tr->daily == null or $tr->daily == 0)
                  0
              @else
                  {{$tr->daily}}
              @endif
          </td>
          <td>
              @if($tr->days == null or $tr->days == 0)
                  0
              @else
                  {{$tr->days}} {{__("days")}}
              @endif
          </td>
          <td>
              @if(($tr->daily * $tr->days)  == null or ($tr->daily * $tr->days) == 0)
                  0
              @else
                  {{($tr->daily * $tr->days)}}
              @endif
          </td>
      </tr>
    </tbody>
  </table>
  <br><br>
  <table >
    <tr>
      <th>
        {{__("Subtotal")}}
      </th>
      <td>
          {{$tr->total}}
      </td>
    </tr>
    <tr>
      <th>
        {{__("VAT")}} 25%
      </th>
      <td>
          {{$tr->total * 0.25}}
      </td>
    </tr>
    <tr>
      <th>
        {{__("Total")}}
      </th>
      <td>
          <b>{{$tr->total}} SEK</b>
      </td>
    </tr>
  </table>
</main>

</body>
</html>
