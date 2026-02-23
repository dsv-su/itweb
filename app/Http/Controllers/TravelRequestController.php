<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Dashboard;
use App\Models\SettingsFo;
use App\Models\TravelRequest;
use App\Models\User;
use App\Workflows\DSVRequestWorkflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Statamic\View\View;
use Workflow\WorkflowStub;

class TravelRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('show')->except(['create', 'resume', 'submit']);
        $this->middleware(['checklang', 'locale']);
    }

    /**
     * Show the TravelRequest form for a given user.
     *
     * @param  int  $id
     * @return \Statamic\View\View
     */
    public function show($id)
    {
        $dashboard = Dashboard::find($id);
        $tr = TravelRequest::find($dashboard->request_id);
        if($tr->state == 'manager_returned' or $tr->state == 'head_returned' or $tr->state == 'fo_returned') {
            $formtype = 'returned';
        } else {
            $formtype = 'show';
        }


        return (new \Statamic\View\View)
            ->template('requests.travel.show')
            ->layout('mylayout')
            ->with(['tr' => $tr, 'formtype' => $formtype]);
    }

    public function resume(TravelRequest $tr)
    {
        $viewData = $this->prepareTravelRequestData();

        // Fetching dashboard
        $dashboard = Dashboard::where('request_id', $tr->id)->first();
        $viewData['type'] = 'resume';
        $viewData['tr'] = $tr;
        $viewData['dashboard'] = $dashboard;

        return $this->createView('requests.travel.create', 'mylayout', $viewData);
    }

    public function create()
    {
        $viewData = $this->prepareTravelRequestData();
        $viewData['type'] = 'create';

        return $this->createView('requests.travel.create', 'mylayout', $viewData);
    }

    private function prepareTravelRequestData()
    {
        // Fetching countries
        $countries = Country::all();

        $roleIdsUnitHead = $this->getUserIdsByGroup('enhetschef');
        $unitheads = User::whereIn('id', $roleIdsUnitHead)->get();

        return [
            'countries' => $countries,
            'unitheads' => $unitheads,
        ];
    }

    public function submit(Request $request)
    {
        // Ensure the request method is POST
        $this->validateRequest($request);

        // Find or create the financial officer
        $fo = SettingsFo::find(1);

        // Create or update TravelRequest
        $travelRequestData = $request->only([
            'name', 'purpose', 'project', 'country', 'paper', 'contribution',
            'other_costs', 'days', 'flight', 'hotel', 'daily',
            'conference', 'other_costs', 'total'
        ]);

        //Domestric travel
        if($request->countrytype == 'domestic') {
            $travelRequestData['country'] = 'Sverige';
        }


        // Convert dates to unix format
        if($request->departure && $request->return) {
            $departureDate = Carbon::createFromFormat('d/m/Y', $request->departure)->timestamp;
            $returnDate = Carbon::createFromFormat('d/m/Y', $request->return)->timestamp;
            $travelRequestData['departure'] = $departureDate;
            $travelRequestData['return'] = $returnDate;
        }
        // Timestamp request
        $travelRequestData['created'] = Carbon::createFromFormat('d/m/Y', now()->format('d/m/Y'))->timestamp;

        //Set initial state
        $travelRequestData['state'] = 'submitted';

        $travelRequest = TravelRequest::find($request->id);
        if (!$travelRequest) {
            $travelRequest = TravelRequest::create($travelRequestData);
        } else {
            $travelRequest->update($travelRequestData);
        }

        // Find or create Dashboard instance
        $dashboardData = [
            'request_id' => $travelRequest->id,
            'name' => $request->name,
            'created' => Carbon::createFromFormat('d/m/Y', now()->format('d/m/Y'))->timestamp,
            'state' => 'submitted',
            'status' => 'unread',
            'type' => 'travelrequest',
            'user_id' => auth()->id(),
            'manager_id' => $request->project_leader,
            'fo_id' => $fo->user_id,
            'head_id' => $request->unit_head,
            'vice_id' => $this->getViceHeadUserId()
        ];

        $dashboard = Dashboard::where('request_id', $request->id)->first();
        if (!$dashboard) {
            $dashboard = Dashboard::create($dashboardData);
        } else {
            $dashboard->update($dashboardData);
        }

        // Create and start workflow
        $workflow = $this->createAndStartWorkflow($dashboard);

        return redirect()->route('statamic.site');
    }

    protected function validateRequest(Request $request)
    {
        $rules = [
            'purpose' => 'required',
            'project_leader' => 'required',
            'unit_head' => 'required',
        ];

        $rules['country'] = ['required_without:countrytype'];
        $rules['countrytype'] = ['required_without:country'];

        $messages = [
            'country.required_without' => 'The country selection is required when your travel is international.',
            'countrytype.required_without' => 'The countrytype field is required when country is not present.',
        ];

        return $this->validate($request, $rules, $messages);
    }

    protected function createAndStartWorkflow($dashboard)
    {
        $workflow = WorkflowStub::make(DSVRequestWorkflow::class);
        $dashboard->workflow_id = $workflow->id();
        $dashboard->save();
        $workflow->start($dashboard);
        $workflow->submit();
        return $workflow;
    }

    private function createView($template, $layout, $data)
    {
        return (new View)->template($template)->layout($layout)->with($data);
    }

    private function getUserIdsByGroup($group)
    {
        return DB::table('group_user')->where('group_id', $group)->pluck('user_id');
    }

    private function getViceHeadUserId(): string
    {
        return DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
    }
}
