<?php

// config for MystamystInc/ModelPolicyGenerator
return [
    // Define your configuration options here
    'permissions' => [
        'view',
        'view_any',
        'create',
        'update',
        'restore',
        'restore_any',
        'replicate',
        'reorder',
        'delete',
        'delete_any',
        'force_delete',
        'force_delete_any',
    ],
    'models_directory' => 'app/Models',
    'policies_directory' => 'app/Policies',
];
