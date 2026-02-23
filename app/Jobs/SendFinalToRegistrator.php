<?php

namespace App\Jobs;

use App\Mail\RegistratorFinalApproval;
use App\Models\SettingsVice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SendFinalToRegistrator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $dsv, $user, $dashboard, $filePath;

    public function __construct($user, $dashboard, $filePath)
    {
        $this->user = $user;
        $this->dashboard = $dashboard;
        $this->filePath = $filePath;
        $this->dsv = SettingsVice::first();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //Registrator
        Mail::to($this->dsv->registrator)->send(new RegistratorFinalApproval($this->user,  $this->dashboard, $this->filePath));
        File::delete(public_path('download/' . $this->dashboard->request_id . '/'. 'ProjectProposal-' . $this->dashboard->name . '.zip'));
    }
}
