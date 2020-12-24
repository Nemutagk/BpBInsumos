<?php
namespace Nemutagk\BpBInsumos\Service;

class AccountService extends HttpService
{
	protected $token;
	protected $url;

	public function __construct() {
		$this->token = env('ACCOUNT_API_TOKEN');
		$accountUrl = env('ACCOUNT_API_URL');

		if (substr($accountUrl,strlen($accountUrl)-1, 1) != '/')
			$accountUrl .= '/';

		$this->url = $accountUrl;
	}
}