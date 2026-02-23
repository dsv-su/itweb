<?php

namespace App\Http\Controllers;

use App\Models\BudgetTemplate;
use App\Models\SettingsOh;
use App\Models\SettingsVice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ViceController extends Controller
{
    public function __construct()
    {
        //$this->middleware('vicehead');
    }

    public function settings()
    {
        //Financial officers
        $roleIds = DB::table('group_user')->where('group_id', 'ekonomi')->pluck('user_id');
        $viewData['fos'] = User::whereIn('id', $roleIds)->get();

        $roleId = DB::table('role_user')->where('role_id', 'vice_head')->pluck('user_id');
        $viewData['vicehead'] = User::find($roleId);
        $viewData['oh'] = SettingsOh::first();
        $viewData['template'] = BudgetTemplate::firstOrCreate(
                                        ['name' => 'DSVBudgetTemplate'],
                                        ['files' => []]);

        $viewData['dsv'] = SettingsVice::firstOrCreate();


        return (new \Statamic\View\View)
            ->template('requests.vice.settings')
            ->with($viewData);
    }

    public function oh(Request $request)
    {
        $validated = $request->validate([
            'oh_max' => 'required|integer|min:1|max:100'
        ]);

        $oh = SettingsOh::first();
        $oh->oh_max = $request->oh_max;
        $oh->oh_eu = $request->oh_eu;
        $oh->save();

        return redirect()
            ->back()
            ->with('success', 'Overhead settings updated.');
    }

    public function registrator(Request $request)
    {

        $validated = $request->validate([
            'dsv_registrator' => 'required|string'
        ]);

        $dsv = SettingsVice::first();
        $dsv->registrator = $request->dsv_registrator;
        $dsv->save();

        return redirect()
            ->back()
            ->with('success', 'Registrator email updated.');
    }

    public function seed()
    {
        Artisan::call('proposal-seed');
        return redirect()->back();
    }

    public function reset()
    {
        Artisan::call('clear-test');
        return redirect()->back();
    }

    private function prepareViewData()
    {

    }

}
