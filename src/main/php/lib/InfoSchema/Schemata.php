<?php
require_once __DIR__ . '/../databean/DataBean.php';
require_once __DIR__ . '/../databean/Lookup.php';
require_once __DIR__ . '/../field/StringField.php';
require_once 'SchemataKey.php';
class Schemata implements DataBean {

	/**
	 * @var SchemataKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $schema;

	public function __construct() {
		$this->key = new SchemataKey();
	}

	/**
	 * @return SchemataKey
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new SchemataByNameLookup(null)
		];
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('SCHEMA_NAME')
		];
	}

}
class SchemataByNameLookup implements Lookup {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name;
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('name'))->setSqlName('SCHEMA_NAME')
		];
	}

}
