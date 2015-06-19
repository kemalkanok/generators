<?php namespace Kanok\Generators;

use Kanok\Generators\Console\GenerateMvcConsole;
use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerMvcGenerator();
	}

    /**
     * Defines the mvc generator console command
     *
     * @return void
     */
    function registerMvcGenerator()
    {
        $this->app->singleton('command.kanok.mvc.generate', function ($app) {
            return $app[get_class(new GenerateMvcConsole())];
        });
        $this->commands('command.kanok.mvc.generate');
    }
}