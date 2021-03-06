<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseLookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class SchemataByNameLookup extends BaseLookup {

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
