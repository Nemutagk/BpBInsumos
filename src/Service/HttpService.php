<?php
namespace Nemutagk\BpBInsumos\Service;

use Log;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\{ClientException,RequestException};

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
			Log::info('HttpServiceConfig: ',[
				'url' => $url
				,'token' => $this->token
				,'payload' => $payload
			]);

			if (!empty($payload) || !empty($requestConfig)) {
				if (!empty($payload))
					$requestConfig = array_merge($requestConfig,[
						'json'=>$payload
					]);

				// Log::info('requestConfig', $requestConfig);

				$response = $client->$method($url,$requestConfig);
			}else
				$response = $client->$method($url);

			return ['success'=>true,'data'=>json_decode($response->getBody()->getContents(), true), 'rawResponse'=>$response];
		}catch(ClientException $e) {
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(RequestException $e) {
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(Exception $e) {
			throw new HttpException($e->getMessage());
		}
	}
}