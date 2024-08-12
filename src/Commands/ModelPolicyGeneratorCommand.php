<?php

namespace MystamystInc\ModelPolicyGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModelPolicyGeneratorCommand extends Command
{
    protected $signature = 'myst:generate';

    protected $description = 'Generate model policies with permissions';

    public function handle()
    {
        $modelsDirectory = config('model-policy-generator.models_directory', 'app/Models');
        $policiesDirectory = config('model-policy-generator.policies_directory', 'app/Policies');
        $permissions = config('model-policy-generator.permissions', []);

        // Ensure we have permissions
        if (empty($permissions)) {
            $this->error('No permissions defined in the configuration.');
            return self::FAILURE;
        }

        // Check if the models directory exists
        if (!File::isDirectory(base_path($modelsDirectory))) {
            $this->error("Models directory does not exist: {$modelsDirectory}");
            return self::FAILURE;
        }

        // Check if the policies directory exists, if not, create it
        if (!File::isDirectory(base_path($policiesDirectory))) {
            File::makeDirectory(base_path($policiesDirectory), 0755, true, true);
            $this->info("Policies directory created: {$policiesDirectory}");
        }

        // Get only files in the direct models directory
        $modelFiles = File::files(base_path($modelsDirectory));

        foreach ($modelFiles as $modelFile) {
            $modelName = $modelFile->getBasename('.php');
            $policyName = $modelName . 'Policy';
            $policyPath = base_path($policiesDirectory . '/' . $policyName . '.php');

            if (!File::exists($policyPath)) {
                $this->generatePolicy($modelName, $policyName, $permissions);
                $this->info("Generated policy for {$modelName}");
            } else {
                $this->warn("Policy for {$modelName} already exists");
            }
        }

        return self::SUCCESS;
    }

    protected function generatePolicy($modelName, $policyName, $permissions)
    {
        $policyTemplate = $this->loadPolicyTemplate();
        $policyMethods = $this->generatePolicyMethods($modelName, $permissions);

        $policy = str_replace(
            ['{{modelName}}', '{{policyMethods}}', '{{modelNamespace}}'],
            [$modelName, $policyMethods, $this->getModelNamespace($modelName)],
            $policyTemplate
        );

        // Put the generated policy in the policies directory
        File::put(base_path('app/Policies/' . $policyName . '.php'), $policy);
    }

    protected function loadPolicyTemplate()
    {
        return '<?php

namespace App\Policies;

use {{modelNamespace}};
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class {{modelName}}Policy
{
    use HandlesAuthorization;

{{policyMethods}}
}';
    }

    protected function generatePolicyMethods($modelName, $permissions)
    {
        $methods = '';
        foreach ($permissions as $permission) {
            $methods .= $this->generatePolicyMethod($modelName, $permission);
        }
        return $methods;
    }

    protected function generatePolicyMethod($modelName, $permission)
    {
        $methodName = Str::camel($permission);
        $modelParam = !in_array($permission, ['view_any', 'create', 'delete_any', 'force_delete_any', 'restore_any', 'reorder'])
            ? ", {$modelName} \${$this->getModelVariableName($modelName)}"
            : '';

        $docBlock = $this->generateDocBlock($permission, $modelName);

        return "
    {$docBlock}
    public function {$methodName}(User \$user{$modelParam}): bool
    {
        return \$user->can('{$permission}_{$this->getModelVariableName($modelName)}');
    }
";
    }

    protected function generateDocBlock($permission, $modelName)
    {
        $action = str_replace('_', ' ', $permission);
        return "    /**
     * Determine whether the user can {$action} {$this->getModelVariableName($modelName)}.
     */";
    }

    protected function getModelVariableName($modelName)
    {
        return lcfirst($modelName);
    }

    protected function getModelNamespace($modelName)
    {
        return "App\\Models\\{$modelName}";
    }
}
