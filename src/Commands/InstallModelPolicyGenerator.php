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

        $this->info('Source configuration file path: ' . $configPath);
        $this->info('Target configuration file path: ' . $targetPath);

        if (!File::exists($targetPath)) {
            File::copy($configPath, $targetPath);
            $this->info('Model Policy Generator configuration file published successfully.');
            $this->info('Configuration file copied from:');
            $this->info($configPath);
            $this->info('To:');
            $this->info($targetPath);
        } else {
            $this->warn('Model Policy Generator configuration file already exists at:');
            $this->warn($targetPath);
        }

        return 0;
    }
}
