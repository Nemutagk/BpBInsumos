<?php
namespace Nemutagk\BpBInsumos\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Access extends Model
{
	protected $collection = null;
	public $timestamps = true;
	protected $connection = null;
	protected $guarded = [];

	public function __construct() {
		$this->collection = env('MLOGGER_COLLECTION_ACCESS');
		$this->connection = env('MLOGGER_CONNECTION');

		parent::__construct();
	}

	public function setAttribute($key,$value) {
		$this->attributes[$key] = $value;
	}
}