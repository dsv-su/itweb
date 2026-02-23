<?php

namespace App\Services\Settings;

use App\Models\System;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConfigurationHandler extends Model
{
    private function getFiles($dir)
    {
        //Get list of filenames
        $process = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $files = array();

        foreach ($process as $file) {
            if ($file->isDir()){
                continue;
            }

            $files[] = $file->getFilename();

        }
        return $files;
    }

    public function check_system()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('systems')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->system();
    }

    public function system()
    {
        $plugindir = base_path().'/systemconfig/';
        $list = $this->getFiles($plugindir);
        foreach ($list as $filename) {
            // Read the .ini file and store in table
            if (substr($filename, -3) == 'ini') {
                $file = $plugindir . $filename;
                if (!file_exists($file)) {
                    $file = $plugindir . $filename . '.example';
                }
                $config = parse_ini_file($file, true);
                //dd($config);
                $system = new System();
                foreach ($config as $configkey => $configvalue) {
                    $config = json_encode($configvalue);
                    $config = json_decode($config);

                    //Store in Plugin
                    $systemtable = $system->getFillable();
                    foreach ($config as $key => $item) {
                        foreach ($systemtable as $systemitem) {
                            if ($systemitem == $key) {
                                if($item == "") $item = 'null';
                                $system->$systemitem = $item;
                            }
                        }
                    }
                }
                $system->save();
            }
        }
    }
}
