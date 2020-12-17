<?php
namespace Nemutagk\BpBInsumos\Exception;

use Exception;

class HttpErrorException extends Exception
{
	protected $response;
	protected $error;
	protected $httpCode;

	public function __construct($message, $httpCode = 500, $error=null, $response=null, $code=0, Exception $previus=null) {
		$this->response = $response;
		$this->error = $error;
		$this->httpCode = $httpCode;

		parent::__construct($message, $code, $previus);
	}

	public function getError() {
		return $this->error;
	}

	public function getResponse() {
		return $this->response;
	}

	public function getHttpCode() {
		return $this->httpCode;
	}
}