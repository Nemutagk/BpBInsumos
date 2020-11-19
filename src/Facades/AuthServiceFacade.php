<?php
namespace Nemutagk\BpBInsumos\Facades;

use Illuminate\Support\Facades\Facade;

class AuthServiceFacade extends Facade
{
	protected static function getFacadeAccessor() {
		return 'AuthService';
	}
}