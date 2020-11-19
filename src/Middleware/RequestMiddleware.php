<?php
namespace Nemutagk\BpBInsumos\Middleware;

use Log;
use Closure;
use Nemutagk\BpBInsumos\Logger;

class RequestMiddleware
{
	public function handle($request, Closure $next) {
		$headers = json_encode($request->header());

		Logger::MakeRequestHash();

		if (strpos($headers, 'ELB-HealthChecker') === false) {
			$requestAll = $request->all();

			if (isset($requestAll['password']))
				$requestAll['password'] = '*******';

			if (isset($requestAll['password_confirmation']))
				$requestAll['password_confirmation'] = '*******';

			$accessRequest = [
	            'path' => $request->fullUrl()
	            ,'method' => $request->method()
	            ,'headers' => $request->header()
	            ,'payload' => $requestAll
	            ,'client' => $request->ip()
	        ];

	        Log::info('RequestAccessInfo: ', $accessRequest);
		}

		return $next($request);
	}
}