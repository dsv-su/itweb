<?php

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

return [

    'connection' => $system_config['statamic']['eloquent_connection'],
    'table_prefix' => env('STATAMIC_ELOQUENT_PREFIX', ''),

    'addon_settings' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\AddonSettings\AddonSettingsModel::class,
    ],

    'asset_containers' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Assets\AssetContainerModel::class,
    ],

    'assets' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Assets\AssetModel::class,
        'asset' => \Statamic\Eloquent\Assets\Asset::class,
    ],

    'blueprints' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Fields\BlueprintModel::class,
        'namespaces' => 'all',
    ],

    'collections' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Collections\CollectionModel::class,
        'update_entry_order_queue' => 'default',
        'update_entry_order_connection' => 'default',
    ],

    'collection_trees' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Structures\TreeModel::class,
        'tree' => \Statamic\Eloquent\Structures\CollectionTree::class,
    ],

    'entries' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Entries\UuidEntryModel::class,
        'entry' => \Statamic\Eloquent\Entries\Entry::class,
        'map_data_to_columns' => false,
    ],

    'fieldsets' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Fields\FieldsetModel::class,
    ],

    'forms' => [
        'driver' => 'eloquent',
        'model'  => \Statamic\Eloquent\Forms\FormModel::class,
    ],

    'form_submissions' => [
        'driver' => 'eloquent',
        'model'  => \Statamic\Eloquent\Forms\SubmissionModel::class,
    ],

    'global_sets' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Globals\GlobalSetModel::class,
    ],

    'global_set_variables' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Globals\VariablesModel::class,
    ],

    'navigations' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Structures\NavModel::class,
    ],

    'navigation_trees' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Structures\TreeModel::class,
        'tree' => \Statamic\Eloquent\Structures\NavTree::class,
    ],

    'revisions' => [
        'driver' => 'file',
        'model' => \Statamic\Eloquent\Revisions\RevisionModel::class,
    ],

    'taxonomies' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Taxonomies\TaxonomyModel::class,
    ],

    'terms' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Taxonomies\TermModel::class,
    ],

    'tokens' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Tokens\TokenModel::class,
    ],

    'sites' => [
        'driver' => 'eloquent',
        'model' => \Statamic\Eloquent\Sites\SiteModel::class,
    ],
];
