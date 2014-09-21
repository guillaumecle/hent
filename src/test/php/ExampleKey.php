<?php
require_once __DIR__.'/../../main/php/lib/databean/Key.php';
require_once __DIR__.'/../../main/php/lib/field/ColumnType.php';
class ExampleKey implements Key {

	private $id;

	public function __construct($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function getFields() {
		return [
			new BaseField('id', ColumnType::integer())
		];
	}

}
