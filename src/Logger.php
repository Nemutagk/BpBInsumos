<?php
namespace Nemutagk\BpBInsumos;

use Monolog\Logger as MonoLogger;

class Logger
{
	public static function MakeRequestHash() {
		$hash = bin2hex(random_bytes(20));

		config(['mlogger.hash'=>$hash]);
	}

	public function __invoke($config) {
		$logger = new MonoLogger('mongodb');
        $logger->pushHandler(new Handler\EloquentHandler());

        return $logger;
	}
}