<?php
function getBearerToken($request) {
	return strpos($request->header('Authorization'), 'Bearer') !== false ? substr($request->header('Authorization'), 7) : $request->header('Authorization');
}

function getNameClass($class) {
	$partes = explode('\\', get_class($class));
	return array_pop($partes);
}

function exception_error($e) {
	$class = explode('\\',get_class($e));

	$payload = [
	 	'error'=>$e->getMessage()
	 	,'file'=>$e->getFile()
	 	,'line'=>$e->getLine()
	 	,'code'=>$e->getCode()
	 	,'trace' => $e->getTraceAsString()
	 ];

	 if (method_exists($e, 'getResponse'))
	 	$payload['http_response'] = $e->getResponse();

	 if (method_exists($e, 'getHttpCode'))
	 	$payload['http_code_response'] = $e->getHttpCode();

	 \Log::error('Error en sistema ('.array_pop($class).'): ',$payload);
}

function return_exception_error($e, $code=500) {
	Log::info('return_exception_error');

	$limitTrace = env('ERROR_TRACE_LIMIT',20);
	$trace = [];

	for($i=0; $i<$limitTrace; $i++) {
		$trace[] = $e->getTrace()[$i];
	}

	$payload = [
		'success' => false
		,'message' => 'Error al procesar el request'
		,'error' => $e->getMessage()
		,'trace' => $trace
	];

	if (env('APP_ENV') == 'production') {
		unset($payload['trace']);

		if (strpost($payload['error'], 'SQLSTATE') !== false)
			$payload['error'] = 'database error';
	}

	return response($payload, $code);
}

function arrayKeyLowerToUpper($arr) {
	$newArr = [];
	if (range(0, count($arr)-1) !== $arr) {
		foreach($arr as $key => $val) {
			if (!is_array($val))
				$newArr[strtoupper($key)] = $val;
			else
				$newArr[strtoupper($key)] = arrayKeyLowerToUpper($val);
		}
	}else {
		foreach($arr as $item) {
			if (!is_array($item))
				$newArr[] = $item;
			else
				$newArr[] = arrayKeyLowerToUpper($item);
		}
	}

	return $newArr;
}

function arrayKeyLower($arr) {
	$tmpArray = [];
	foreach($arr as $key => $value) {
		if (!is_array($value))
			$tmpArray[lcfirst($key)] = $value;
		else if (array_keys($value) === range(0, count($value)-1)) {
			$tmpValue = [];
			foreach($value as $subArray) {
				$tmpValue[] = $this->toLower($subArray);
			}
			$tmpArray[lcfirst($key)] = $tmpValue;
		}else {
			$tmpArray[lcfirst($key)] = $this->toLower($value);
		}
	}

	return $tmpArray;
}

function arrayReMap($arr, $map) {
	$newArr = [];
	if (range(0, count($arr)-1) !== $arr) {
		foreach($arr as $key => $val) {
			if (!is_array($val)) {
				if (isset($map[$key]))
					$newArr[$map[$key]] = $val;
				else
					$newArr[$key] = $val;
			}else {
				if (isset($map[$key]))
					$newArr[$map[$key]] = arrayReMap($val,$map);
				else
					$newArr[$key] = arrayReMap($val,$map);
			}
		}
	}else {
		foreach($arr as $val) {
			if (!is_array($val))
				$newArr[] = $val;
			else
				$newArr[] = arrayReMap($val, $map);
		}
	}

	return $newArr;
}

function str_rand($length=8, $alpha=true) {
	if ($alpha)
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	else
		$characters = '0123456789';

    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function startProcess() {
	return \Carbon\Carbon::now();
}

function endProcess(\Carbon\Carbon $start) {
	return $start->diffInSeconds(\Carbon\Carbon::now());
}

function data_set_utf8($data) {
	if (is_array($data)) {
		foreach($data as $key => $value) {
			if (is_string($value)) {
				if (mb_detect_encoding($value, 'UTF-8', true)) {
					$data[$key] = $value;
				}else {
					$data[$key] = utf8_encode($value);
				}
			}else if (is_array($value) || is_object($value)) {
				$data[$key] = data_set_utf8($value);
			}else {
				$data[$key] = $value;
			}
		}
	}else {
		$keys = array_keys(get_object_vars($data));

		foreach($keys as $key) {
			$data->$key = data_set_utf8($data->$key);
		}
	}

	return $data;
}