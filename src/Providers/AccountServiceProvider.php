<?php

namespace Nemutagk\BpBInsumos\Providers;

use Illuminate\Support\ServiceProvider;
use Nemutagk\BpBInsumos\Service\AccountService;

class AccountServiceProvider extends ServiceProvider
{
	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AccountService', function() {
        	return new AccountService();
        });
    }
}