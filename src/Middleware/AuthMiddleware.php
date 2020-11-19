<?php
namespace Nemutagk\BpBInsumos\Middleware;

class AuthMiddleware
{
	public function handle($request, Closure $next, $app, $permission) {
		$header = $request->header();

		$token = isset($header['authorization']) ? str_replace('Bearer ', '', $header['authorization'][0]) : null;

		if ($token !== null ) {
			$cliente = new Client();
			$res = $cliente->post(env('ACCOUNT_API_URL').'auth/validate',[
				'json'=> [
					'token' => $token
					,'aplicacion' => $app
					,'permiso' => $permission
				]
			]);

			if ($res->getStatusCode() == 200) {
				$data = json_decode($res->getBody()->getContents(), true);
				// Log::info('data: '.print_r($data, true));

				if ($data)  {
					if ($data['success']) {
						if ($data['data']) {
							$request->attributes->add(['auth'=>$data['data']]);
							(Auth::getInstance())->setAuth($data['data']);
						}

						$response = $next($request);
					}else {
						unset($data['success']);
						$response = response()->json($data, $data['code']);
					}
				}else {
					Log::error('Error al obtener info de accout: '.print_r($res->getBody()->getContents(), true));
					$response = response()->json(['message'=>'Acceso no autorizado'], 401);
				}
			}else {
				Log::error('Error en response: '.print_r($res->getBody(), true));
				$response = response()->json(['message'=>'Acceso no autorizado'], 401);
			}
		}else {
			Log::error('No existe el token!');
			$response = response()->json(['message'=>'Acceso no autorizado'], 401);
		}

		return $response;
	}
}