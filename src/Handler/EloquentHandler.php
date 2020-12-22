<?php
namespace Nemutagk\BpBInsumos\Handler;

use Monolog\Handler\AbstractProcessingHandler;

use Nemutagk\BpBInsumos\Models\Logger;

class EloquentHandler extends AbstractProcessingHandler
{
	protected function write($config) : void {
		$log = new Logger();

		$json = json_encode($config, JSON_PRETTY_PRINT);
		$arraySanitized = json_decode($json, true);

		$config = $this->clear_keys($arraySanitized ?? []);

		$config['hash'] = config('mlogger.hash','n/a');

		foreach($config as $key => $valor) {
			$log->$key = $valor;
		}

		$log->save();
	}

	protected function clear_keys(array $array, array $search = ['.'], array $replace = ['-']) {
		$newArray = [];

		foreach($array as $key => $value) {
			$key = str_replace($search, $replace, $key);

			if (!is_array($value))
				$newArray[$key] = $value;
			else
				$newArray[$key] = $this->clear_keys($value, $search, $replace);
		}

		return $newArray;
	}
}
