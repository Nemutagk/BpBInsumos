<?php
namespace Nemutagk\BpBInsumos\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Logger extends Model
{
	protected $collection = null;
	public $timestamps = true;
	protected $connection = null;

	public function __construct() {
		$this->collection = env('MLOGGER_COLLECTION');
		$this->connection = env('MLOGGER_CONNECTION');

		parent::__construct();
	}
}