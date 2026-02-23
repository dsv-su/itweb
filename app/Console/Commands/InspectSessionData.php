<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class InspectSessionData extends Command
{
    protected $signature = 'session:inspect';
    protected $description = 'Inspect session data for non-serializable objects';

    public function handle()
    {
        $sessionData = Session::all();
        $this->inspectSessionData($sessionData);
        $this->info('Session inspection complete. Check logs for details.');
    }

    private function inspectSessionData(array $sessionData)
    {
        foreach ($sessionData as $key => $value) {
            if (is_object($value)) {
                $this->checkIfSerializable($key, $value);
            } elseif (is_array($value)) {
                $this->inspectSessionData($value); // Recursively inspect arrays
            }
        }
    }

    private function checkIfSerializable($key, $object)
    {
        try {
            serialize($object);
            $this->info("Session key '{$key}' contains a serializable object.");
        } catch (\Exception $e) {
            $this->error("Session key '{$key}' contains a non-serializable object: " . get_class($object));
        }
    }
}
