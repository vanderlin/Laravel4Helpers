<?php namespace Vanderlin\Helper;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class HelperServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}


	public function boot() {
		$this->package('vanderlin/helper');
        AliasLoader::getInstance()->alias('Helper', 'Vanderlin\Helper\Helper');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}