<?php
require_once __DIR__.'/../databean/Key.php';
require_once __DIR__.'/../fielder/Fielder.php';
require_once __DIR__.'/../field/ColumnType.php';
class TablesKey implements Key {

	/**
	 * @var TablesKeyFielder
	 */
	private static $fielder;
	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param $name string
	 * @param $schema string
	 */
	function __construct($schema, $name) {
		$this->schema = $schema;
		$this->name = $name;
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
	 * @return Fielder
	 */
	public function getFielder() {
		if (!isset(TablesKey::$fielder)) {
			TablesKey::$fielder = new TablesKeyFielder();
		}
		return TablesKey::$fielder;
	}

}
class TablesKeyFielder implements Fielder {

	public function getFields() {
		return [
			(new BaseField('name', ColumnType::string()))->setSqlName('TABLE_NAME'),
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA')
		];
	}

}