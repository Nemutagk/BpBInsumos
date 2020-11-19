<?php
namespace Nemutagk\BpBInsumos\Support;

use Log;
use Illuminate\Http\Request;

class Auth
{
	private static $instance=null;
	private $auth;

	public static function getInstance() {
		return is_null(self::$instance) ? (self::$instance = new self()) : self::$instance;
	}

	private function __construct() {

	}

	public function setAuth(array $auth) {
		$this->auth = $auth;
	}

	public function getAuth() {
		return $this->auth;
	}

	public function getUsuario() {
		$user = isset($this->auth['usuario']) ? $this->auth['usuario'] : null;

		if (isset($user['roles']))
			unsert($user['roles']);

		if (isset($user['permisos']))
			unsert($user['permisos']);

		if (isset($user['reglas']))
			unsert($user['reglas']);

		if (isset($user['aplicaciones']))
			unsert($user['aplicaciones']);

		return $user;
	}

	public function getRoles() {
		return isset($this->auth['roles']) ? $this->auth['roles'] : null;
	}

	public function getPermisos() {
		return isset($this->auth['permisos']) ? $this->auth['permisos'] : null;
	}

	public function getAplicaciones() {
		return isset($this->auth['aplicaciones']) ? $this->auth['aplicaciones'] : null;
	}

	public function can($app, $permiso) {
		$permisos = $this->getPermisos();

		$isValid = false;

		if ($permisos) {
			// Log::info('permisos: '.print_r($permisos, true));

			$hash = md5(Str::normalize($app).$permiso);
			// Log::info('Hash: '.print_r($hash, true));

			$hashAdmin = md5(Str::normalize($app).'*');
			// Log::info('HashAdmin: '.print_r($hashAdmin, true));

			if (isset($permisos[$hash]))
				if ($permisos[$hash]['acceso'] == 1)
					$isValid = true;
			else if (isset($permisos[$hashAdmin]))
				$isValid = true;
		}

		return $isValid;
	}
}