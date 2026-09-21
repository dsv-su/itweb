<?php

namespace App\Http\Controllers;

use App\Imports\ProjectsRefreshImport;
use App\Models\Dashboard;
use App\Models\Project;
use App\Models\ProjectProposal;
use App\Models\SettingsFo;
use App\Models\SettingsFoEu;
use App\Models\TravelRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
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
                    'tr' => TravelRequest::findOrFail($id),
                    'formtype' => 'show',
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
        // ->layout('mylayout');
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

    public function projects(Request $request)
    {
        App::setLocale('sv');

        $search = trim((string) $request->query('search', ''));

        return (new StatamicView)->template('requests.fo.projects')->with([
            'projects' => Project::query()
                ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('project', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('projectleader', 'like', "%{$search}%");
                }))
                ->orderBy('project')->paginate(25)->withQueryString(),
            'editing' => $request->filled('edit') ? Project::findOrFail($request->query('edit')) : null,
            'search' => $search,
        ]);
    }

    public function storeProject(Request $request)
    {
        App::setLocale('sv');

        Project::create($this->validateProject($request));

        return redirect()->route('fo.projects')->with('status', 'Projektet har lagts till.');
    }

    public function updateProject(Request $request, Project $project)
    {
        App::setLocale('sv');

        $project->update($this->validateProject($request, $project));

        return redirect()->route('fo.projects')->with('status', 'Projektet har uppdaterats.');
    }

    public function destroyProject(Project $project)
    {
        App::setLocale('sv');

        $project->delete();

        return redirect()->route('fo.projects')->with('status', 'Projektet har tagits bort.');
    }

    private function validateProject(Request $request, ?Project $project = null): array
    {
        $numberRules = ['required', 'string', 'max:255'];
        // Existing imports may contain duplicate numbers; only validate uniqueness when assigning a number.
        if ($project === null || (string) $request->input('project') !== (string) $project->project) {
            $numberRules[] = Rule::unique('projects', 'project')->ignore($project);
        }

        $data = $request->validate([
            'project' => $numberRules,
            'description' => ['required', 'string', 'max:255'],
            'projectleader' => ['required', 'string', 'max:255'],
            //'status' => ['nullable', 'string', 'max:255'],
        ], [], [
            'project' => 'projektnummer',
            'description' => 'projektbenämning',
            'projectleader' => 'projektledare',
            'status' => 'status',
        ]);

        // The database column is non-nullable, but older projects can have an empty status.
        if (array_key_exists('status', $data) || $project === null) {
            $data['status'] = $data['status'] ?? '';
        }

        return $data;
    }

    public function importProjects(Request $request)
    {
        App::setLocale('sv');

        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240']], [], ['file' => 'Excel-fil']);
        $import = new ProjectsRefreshImport;

        try {
            Excel::import($import, $request->file('file'));
            if ($import->count === 0) {
                throw ValidationException::withMessages(['file' => 'Det första kalkylbladet måste innehålla projektrader.']);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['file' => 'Kalkylbladet kunde inte importeras. Kontrollera filen och försök igen.']);
        }

        return redirect()->route('fo.projects')->with('status', "{$import->count} projekt har importerats.");
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
            // ->layout('mylayout')
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
