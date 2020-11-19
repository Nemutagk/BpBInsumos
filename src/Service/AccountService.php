<?php
namespace Nemutagk\BpBInsumos\Service;

class AccountService extends HttpService
{
	protected $token;
	protected $url;

	public function __construct() {
		$this->token = env('ACCOUNT_API_TOKEN');
		$this->url = env('ACCOUNT_API_URL');
	}
}