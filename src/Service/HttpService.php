<?php
namespace Nemutagk\BpBInsumos\Service;

use Log;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\{ClientException,RequestException,ServerException};

class HttpService
{
	public function request($method, $uri, $payload=[], $config=[], $requestConfig=[]) {
		try {

			if (property_exists($this, 'token'))
				if (isset($config['headers']))
					$config['headers']['Authorization'] = $this->token;
				else
					$config['headers'] = ['Authorization' => $this->token];

			$client = new Client($config);

			$url = $this->url.$uri;

			if (!empty($payload) || !empty($requestConfig)) {
				if (!empty($payload))
					$requestConfig = array_merge($requestConfig,[
						'json'=>$payload
					]);

				$response = $client->$method($url,$requestConfig);
			}else
				$response = $client->$method($url);

			if (!isset($config['isFile']) || !$config['isFile'])
				return ['success'=>true,'data'=>json_decode($response->getBody()->getContents(), true), 'rawResponse'=>$response];
			else
				return ['success'=>true,'data'=>$response->getBody()->getContents(), 'rawResponse'=>$response];
		}catch(ClientException $e) {
			exception_error($e);
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(RequestException $e) {
			exception_error($e);
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(ServerException $e) {
			exception_error($e);
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(Exception $e) {
			exception_error($e);
			throw new HttpException($e->getMessage());
		}
	}
}