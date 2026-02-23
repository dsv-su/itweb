<?php

namespace App\Services\Ldap;

use LdapRecord\Container;
use LdapRecord\Connection;

class LdapContainer
{
    public function bind()
    {
        $connection = new Connection([
            'hosts'    => [$this->host()],
            'username' => $this->username(),
            'password' => $this->password(),
        ]);
        $connection->connect();

        Container::addConnection($connection);
    }

    private function host()
    {
        // Absolute path to the primary config file
        $primaryConfig = base_path('systemconfig/it.ini');
        $exampleConfig = base_path('systemconfig/it.ini.example');

        // Choose the real config if present, otherwise fallback to the example
        $configFile = file_exists($primaryConfig) ? $primaryConfig : $exampleConfig;

        // Parse the INI file with section support
        $system_config = parse_ini_file($configFile, true);

        // Validate parsing result
        if ($system_config === false) {
            throw new \RuntimeException(
                sprintf('Failed to parse configuration file: %s', $configFile)
            );
        }
        return $system_config['sukat']['host'];
    }

    private function username()
    {
        // Absolute path to the primary config file
        $primaryConfig = base_path('systemconfig/it.ini');
        $exampleConfig = base_path('systemconfig/it.ini.example');

        // Choose the real config if present, otherwise fallback to the example
        $configFile = file_exists($primaryConfig) ? $primaryConfig : $exampleConfig;

        // Parse the INI file with section support
        $system_config = parse_ini_file($configFile, true);

        // Validate parsing result
        if ($system_config === false) {
            throw new \RuntimeException(
                sprintf('Failed to parse configuration file: %s', $configFile)
            );
        }

        return $system_config['sukat']['username'];
    }

    private function password()
    {
        // Absolute path to the primary config file
        $primaryConfig = base_path('systemconfig/it.ini');
        $exampleConfig = base_path('systemconfig/it.ini.example');

        // Choose the real config if present, otherwise fallback to the example
        $configFile = file_exists($primaryConfig) ? $primaryConfig : $exampleConfig;

        // Parse the INI file with section support
        $system_config = parse_ini_file($configFile, true);

        // Validate parsing result
        if ($system_config === false) {
            throw new \RuntimeException(
                sprintf('Failed to parse configuration file: %s', $configFile)
            );
        }

        return $system_config['sukat']['password'];
    }
}
