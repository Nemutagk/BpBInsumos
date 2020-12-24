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

			$content = $response->getBody()->getContents();

			return ['success'=>true,'data'=>json_decode($content, true), 'rawResponse'=>$content,'headers'=>$response->getHeaders()];
		}catch(ClientException | RequestException | ServerException $e) {
			exception_error($e);
			throw new HttpException($e->getMessage(), json_decode($e->getResponse()->getBody()->getContents(), true), $e->getResponse()->getStatusCode());
		}catch(Exception $e) {
			exception_error($e);
			throw new HttpException($e->getMessage());
		}
	}
}