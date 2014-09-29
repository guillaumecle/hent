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
	private $schema;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $engine;

	public function __construct() {
		$this->key = new TablesKey();
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
	public function getSchema() {
		return $this->schema;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	public function getFields() {
		return [
			(new BaseField('name', ColumnType::string()))->setSqlName('TABLE_NAME'),
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('engine', ColumnType::string()))->setSQLName('ENGINE')
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new TablesBySchemaLookup(null)
		];
	}

}
class TablesBySchemaLookup implements Lookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @param string $schema
	 */
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
