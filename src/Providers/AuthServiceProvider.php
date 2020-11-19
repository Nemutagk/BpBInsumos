<?php

namespace Nemutagk\BpBInsumos\Providers;

use Illuminate\Support\ServiceProvider;

use Nemtuagk\BpbInsumos\Support\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AuthService', function() {
            return Auth::getInstance();
        });
    }
}
