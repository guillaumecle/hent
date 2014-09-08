<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../databean/Lookup.php';
require_once 'TablesKey.php';
class Tables implements DataBean {

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

	public function getFields() {
		return [
			(new BaseField('engine', ColumnType::string()))->setSQLName('ENGINE')
		];
	}

}
class TablesBySchemaLookup implements Lookup {

	/**
	 * @var string
	 */
	private $schema;

	public function __construct($schema) {
		$this->schema = $schema;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA')
		];
	}

}
