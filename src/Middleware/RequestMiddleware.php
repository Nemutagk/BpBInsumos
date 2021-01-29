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

			if (isset($headers['authorization']))
				$headers['authorization'] = substr($headers['authorization'], 0, 10).'...';

			$accessRequest = [
	            'path' => $request->fullUrl()
	            ,'method' => $request->method()
	            ,'headers' => $headers
	            ,'payload' => $requestAll
	            ,'client' => $request->ip()
	        ];

	        Log::info('RequestAccessInfo: ', $accessRequest);

	        if (strpos($request->fullUrl(), 'bienparabien') === false && strpos($request->fullUrl(), 'bpb') === false)
				return response()->json(['message'=>'Acceso no autorizado'], 401);
		}

		return $next($request);
	}
}