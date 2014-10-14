<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseDatabean;
use Hent\Databean\Lookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class Schemata extends BaseDatabean {

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
