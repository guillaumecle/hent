<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseLookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class TablesBySchemaLookup extends BaseLookup {

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
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA')
		];
	}

}
