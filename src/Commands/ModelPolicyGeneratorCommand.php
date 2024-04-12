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
        $modelsDirectory = config('model-policy-generator.models_directory');
        $policiesDirectory = config('model-policy-generator.policies_directory');
        $permissions = config('model-policy-generator.permissions'); // Provide default permissions if config value is null

        // Check if the models directory exists, if not, create it
        if (!File::isDirectory(base_path($modelsDirectory))) {
            File::makeDirectory(base_path($modelsDirectory), 0755, true, true);
            $this->info("Models directory created: {$modelsDirectory}");
        }

        // Check if the policies directory exists, if not, create it
        if (!File::isDirectory(base_path($policiesDirectory))) {
            File::makeDirectory(base_path($policiesDirectory), 0755, true, true);
            $this->info("Policies directory created: {$policiesDirectory}");
        }

        $models = File::allFiles(base_path($modelsDirectory));

        foreach ($models as $model) {
            $modelName = basename($model->getBasename(), '.php');
            $policyName = $modelName . 'Policy';
            $policyPath = base_path($policiesDirectory . '/' . $policyName . '.php');

            if (!File::exists($policyPath)) {
                $this->generatePolicy($modelName, $policyName, $permissions);
                $this->info("Generated policy for {$modelName}");
            } else {
                $this->warn("Policy for {$modelName} already exists");
            }
        }

        return self::SUCCESS; // Indicate successful execution
    }

    protected function generatePolicy($modelName, $policyName, $permissions)
    {
        $policyTemplate = $this->loadPolicyTemplate();
        $policyMethods = '';

        if (!is_null($permissions) && is_array($permissions)) {
            foreach ($permissions as $permission) {
                $policyMethods .= $this->generatePolicyMethod($modelName, $permission);
            }
        }

        $policy = str_replace(['{{modelName}}', '{{policyMethods}}'], [$modelName, $policyMethods], $policyTemplate);

        // Put the generated policy in the policies directory
        File::put(app_path('Policies/' . '/' . $policyName . '.php'), $policy);
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
