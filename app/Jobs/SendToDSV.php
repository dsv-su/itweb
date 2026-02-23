<?php

namespace App\Jobs;

use App\Mail\DSVNews;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendToDSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $adress, $news;
    /**
     * Create a new job instance.
     */
    public function __construct($adress, $news)
    {
        $this->adress = $adress;
        $this->news = $news;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->adress)->send(new DSVNews($this->news));
    }
}
