<?php

namespace MystamystInc\ModelPolicyGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallModelPolicyGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'myst:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Model Policy Generator package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $configPath = __DIR__ . '/../../config/model-policy-generator.php';
        $targetPath = config_path('model-policy-generator.php');

        if (!File::exists($targetPath)) {
            File::copy($configPath, $targetPath);
            $this->info('Model Policy Generator configuration file published successfully.');
        } else {
            $this->comment('Model Policy Generator configuration file already exists.');
        }

        return 0;
    }
}