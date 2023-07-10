<?php

namespace albawebstudio\PetfinderApi;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * @return void
     */
    public function register(): void
    {
        $configPath = __DIR__ . '/config/petfinder.php';
        $this->mergeConfigFrom($configPath, 'petfinder');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/config/petfinder.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * @return string
     */
    protected function getConfigPath(): string
    {
        return config_path('petfinder.php');
    }

    /**
     * Publish the config file
     *
     * @param $configPath
     * @return void
     */
    protected function publishConfig($configPath): void
    {
        $this->publishes([ $configPath => config_path('petfinder.php') ], 'config');
    }
}