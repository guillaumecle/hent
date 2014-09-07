<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../fielder/Fielder.php';
require_once 'TablesKey.php';
class Tables implements DataBean {

	/**
	 * @var TablesFielder
	 */
	private static $fielder;

	/**
	 * @var TablesKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $engine;

	/**
	 * @param $key TablesKey
	 * @param $engine string
	 */
	public function __construct($key, $engine) {
		$this->key = $key;
		$this->engine = $engine;
	}

	/**
	 * @return TablesKey
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @return Fielder
	 */
	public function getFielder() {
		if (!isset(Tables::$fielder)) {
			Tables::$fielder = new TablesFielder();
		}
		return Tables::$fielder;
	}

}
class TablesFielder implements Fielder {

	public function getFields() {
		return [
			(new BaseField('engine', ColumnType::string()))->setSQLName('ENGINE')
		];
	}

}
