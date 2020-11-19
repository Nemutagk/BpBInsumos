<?php
namespace Nemutagk\BpBInsumos\Facades;

use Illuminate\Support\Facades\Facade;

class AccountServiceFacade extends Facade
{
	protected static function getFacadeAccessor() {
		return 'AccountService';
	}
}