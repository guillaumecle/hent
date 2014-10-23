<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseLookup;
use Hent\Field\StringField;

class KeyColumnsByTableAndName extends  BaseLookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @var
	 */
	private $keyName;

	/**
	 * @param $schema string
	 * @param $tableName string
	 * @param $keyName string
	 */
	function __construct($schema, $tableName, $keyName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
		$this->keyName = $keyName;
	}

	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME'),
			(new StringField('keyName'))->setSqlName('CONSTRAINT_NAME')
		];
	}

}
