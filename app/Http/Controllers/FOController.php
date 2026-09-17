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
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
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

        return match ($dashboard->type) {
            'travelrequest' => (new StatamicView)
                ->template('requests.travel.show')
                ->with([
                    'tr'        => TravelRequest::findOrFail($id),
                    'formtype'  => 'show',
                    'dashboard' => $dashboard,
                ]),

            'projectproposal' => redirect()->action(
                [ReviewController::class, 'pp_view'],
                ['proposal' => ProjectProposal::findOrFail($id)]
            ),

            default => abort(404),
        };
    }

    public function showLocalized(string $lang, $id)
    {
        return $this->show($id);
    }

    public function list()
    {
        return (new StatamicView)
            ->template('requests.fo.list');
            //->layout('mylayout');
    }

    public function svlist()
    {
        return $this->list();
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
        return $this->saveOfficers($request, 'selected_fo', SettingsFo::class);
    }

    public function settings_fo_eu(Request $request)
    {
        return $this->saveOfficers($request, 'selected_fo_eu', SettingsFoEu::class);
    }

    private function saveOfficers(Request $request, string $field, string $model)
    {
        $data = $request->validate([
            $field => ['required', 'array', 'min:1'],
            "$field.*" => [
                'required', 'string', 'distinct', 'exists:users,id',
                Rule::exists('group_user', 'user_id')->where('group_id', 'ekonomi'),
            ],
        ]);

        DB::transaction(function () use ($data, $field, $model) {
            $model::query()->whereNotIn('user_id', $data[$field])->delete();

            foreach (User::whereIn('id', $data[$field])->get() as $user) {
                $model::updateOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $user->name, 'active' => true]
                );
            }
        });

        Cache::forget('fo_ids');

        return back()->with('status', 'Financial officer notification settings updated.');
    }
}
