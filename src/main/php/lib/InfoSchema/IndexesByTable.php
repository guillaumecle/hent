<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseLookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class IndexesByTable extends BaseLookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	function __construct($schema, $tableName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME')
		];
	}

}
