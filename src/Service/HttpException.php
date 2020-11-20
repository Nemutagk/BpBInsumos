<?php
namespace Nemutagk\BpBInsumos\Service;

use Exception;

class HttpException extends Exception
{
	protected $response;
	protected $httpCode;

	public function __construct($message, $response=null, $httpCode = 200, $code=0, Exception $previus=null) {
		$this->response = $response;
		$this->httpCode = $httpCode;

		parent::__construct($message, $code, $previus);
	}

	public function getResponse() {
		return $this->response;
	}

	public function getHttpCode() {
		return $this->httpCode;
	}
}