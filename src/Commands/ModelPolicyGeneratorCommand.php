<?php

namespace MystamystInc\ModelPolicyGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModelPolicyGeneratorCommand extends Command
{
    protected $signature = 'myst-policy:generate';

    protected $description = 'Generate model policies with permissions';

    public function handle()
    {
        $modelsDirectory = config('myst.models_directory');
        $policiesDirectory = config('myst.policies_directory');
        $permissions = config('myst.permissions');

        $models = File::allFiles(app_path($modelsDirectory));

        foreach ($models as $model) {
            $modelName = basename($model->getBasename(), '.php');
            $policyName = $modelName . 'Policy';
            $policyPath = app_path($policiesDirectory . '/' . $policyName . '.php');

            if (!File::exists($policyPath)) {
                $this->generatePolicy($modelName, $policyName, $permissions);
                $this->info("Generated policy for {$modelName}");
            } else {
                $this->warn("Policy for {$modelName} already exists");
            }
        }
    }

    protected function generatePolicy($modelName, $policyName, $permissions)
    {
        $policyTemplate = $this->loadPolicyTemplate();

        $policyMethods = '';
        foreach ($permissions as $permission) {
            $policyMethods .= $this->generatePolicyMethod($modelName, $permission);
        }

        $policy = str_replace(['{{modelName}}', '{{policyMethods}}'], [$modelName, $policyMethods], $policyTemplate);

        File::put(app_path('Policies/' . $policyName . '.php'), $policy);
    }

    protected function loadPolicyTemplate()
    {
        // Load your policy template from a file or a string
        return '<?php

namespace App\Policies;

use App\Models\{{modelName}};
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class {{modelName}}Policy
{
    use HandlesAuthorization;

    {{policyMethods}}
}';
    }

    protected function generatePolicyMethod($modelName, $permission)
    {
        $policyMethod = "
    public function {$permission}_{$modelName}(User \$user, {$modelName} \${$this->getModelVariableName($modelName)})
    {
        // Implement your policy logic here
        return true;
    }";

        return $policyMethod;
    }

    protected function getModelVariableName($modelName)
    {
        return lcfirst($modelName);
    }
}