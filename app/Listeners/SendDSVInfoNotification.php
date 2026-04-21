<?php

namespace App\Listeners;

use App\Jobs\SendToDSV;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class SendDSVInfoNotification
{
    /**
     * Create the event listener.
     */

    protected $file;

    public function handle(object $event): void
    {
        $news = $event;

        if (!isset($news->entry)) {
            return;
        }

        // Identify entry consistently (fallbacks, since Statamic entry id accessor can vary)
        $entryId = method_exists($news->entry, 'id') ? $news->entry->id() : ($news->entry->id ?? null);
        if (!$entryId) {
            return;
        }

        // Only act for English (as you currently do)
        $locale = $news->entry->locale ?? (method_exists($news->entry, 'locale') ? $news->entry->locale() : null);
        if ($locale !== 'English') {
            return;
        }

        if ($news->entry->email_dsv) {
            $this->dispatchOnce($entryId, 'dsv_info', new SendToDSV($this->dsv_info(), $news));
            return;
        }

        if ($news->entry->email_teachers) {
            $this->dispatchOnce($entryId, 'dsv_teachers', new SendToDSV($this->dsv_teachers(), $news));
            return;
        }

        if ($news->entry->email_phd) {
            $this->dispatchOnce($entryId, 'dsv_phd', new SendToDSV($this->dsv_phd(), $news));
            return;
        }
    }

    private function dispatchOnce(string $entryId, string $type, SendToDSV $job): void
    {
        $key = "dsv_entry_saved_notification:{$entryId}:{$type}";

        if (!Cache::add($key, true, now()->addMinutes(2))) {
            return;
        }

        dispatch($job);
    }

    private function dsv_info()
    {
        $this->file = base_path() . '/systemconfig/it.ini';
        if (!file_exists($this->file)) {
            $this->file = base_path() . '/systemconfig/it.ini.example';
        }
        $this->system_config = parse_ini_file($this->file, true);

        return $this->system_config['email']['dsv_info'];
    }

    private function dsv_teachers()
    {
        $this->file = base_path() . '/systemconfig/it.ini';
        if (!file_exists($this->file)) {
            $this->file = base_path() . '/systemconfig/it.ini.example';
        }
        $this->system_config = parse_ini_file($this->file, true);

        return $this->system_config['email']['dsv_teachers'];
    }

    private function dsv_phd()
    {
        $this->file = base_path() . '/systemconfig/it.ini';
        if (!file_exists($this->file)) {
            $this->file = base_path() . '/systemconfig/it.ini.example';
        }
        $this->system_config = parse_ini_file($this->file, true);

        return $this->system_config['email']['dsv_phd'];
    }
}
