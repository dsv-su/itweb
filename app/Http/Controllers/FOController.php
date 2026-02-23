<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\SettingsFo;
use App\Models\SettingsFoEu;
use App\Models\TravelRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Statamic\View\View as StatamicView;

class FOController extends Controller
{
    public function __construct()
    {
        $this->middleware('fo')->except(['list', 'svlist', 'download']);
        $this->middleware('download')->only('download');
    }

    public function show($id)
    {
        $dashboard = Dashboard::where('request_id', $id)->firstOrFail();

        if ($dashboard->type === 'travelrequest') {
            $tr = TravelRequest::findOrFail($id);

            return (new StatamicView)
                ->template('requests.travel.show')
                //->layout('mylayout')
                ->with(['tr' => $tr, 'formtype' => 'show']);
        }

        if ($dashboard->type === 'projectproposal') {
            $proposal = ProjectProposal::find($id);
            return redirect()->action([ReviewController::class, 'pp_view'], ['proposal' => $proposal]);
        }

        abort(404);
    }

    public function list()
    {
        return (new StatamicView)
            ->template('requests.fo.list');
            //->layout('mylayout');
    }

    public function svlist()
    {
        App::setLocale('sv');

        return (new StatamicView)
            ->template('requests.fo.list');
            //->layout('mylayout');
    }

    public function pdfview($id)
    {
        $data = $this->buildTravelPdfData($id);

        return view('requests.travel.pdf', $data);
    }

    public function download($id)
    {
        App::setLocale('sv');

        $data = $this->buildTravelPdfData($id);

        return Pdf::loadView('requests.travel.pdf', $data)
            ->download('travelrequest_'.$data['tr']->id.'.pdf');
    }

    private function buildTravelPdfData($travelRequestId): array
    {
        $tr = TravelRequest::findOrFail($travelRequestId);

        $dashboard = Dashboard::where('request_id', $tr->id)->firstOrFail();

        return [
            'tr' => $tr,
            'user' => User::find($dashboard->user_id),
            'manager' => User::find($dashboard->manager_id),
            'head' => User::find($dashboard->head_id),
        ];
    }

    public function settings()
    {
        $roleIds = DB::table('group_user')
            ->where('group_id', 'ekonomi')
            ->pluck('user_id')
            ->all();

        $financialofficer = User::whereIn('id', $roleIds)->get();

        return (new StatamicView)
            ->template('requests.fo.settings')
            //->layout('mylayout')
            ->with(['fos' => $financialofficer]);
    }

    public function settings_fo(Request $request)
    {
        $data = $request->validate([
            'selected_fo' => ['required', 'string', 'exists:users,id'],
        ]);

        $user = User::findOrFail($data['selected_fo']);

        // Replace truncation with an update-or-create “single active FO” pattern
        /*DB::transaction(function () use ($user) {
            SettingsFo::query()->update(['active' => false]);
            SettingsFo::updateOrCreate(
                ['user_id' => $user->id],
                ['name' => $user->name, 'active' => true]
            );
        });*/
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('settings_fos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $fo = SettingsFo::firstOrCreate(
            ['user_id' => $request->selected_fo],
            [
                'name' => $user->name,
                'active' => true
            ]
        );

        return back();
    }

    public function settings_fo_eu(Request $request)
    {
        $data = $request->validate([
            'selected_fo_eu' => ['required', 'string', 'exists:users,id'],
        ]);

        $user = User::findOrFail($data['selected_fo_eu']);

        /*DB::transaction(function () use ($user) {
            SettingsFoEu::query()->update(['active' => false]);
            SettingsFoEu::updateOrCreate(
                ['user_id' => $user->id],
                ['name' => $user->name, 'active' => true]
            );
        });*/
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('settings_fo_eus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $fo = SettingsFoEu::firstOrCreate(
            ['user_id' => $request->selected_fo_eu],
            [
                'name' => $user->name,
                'active' => true
            ]
        );

        return back();
    }
}
