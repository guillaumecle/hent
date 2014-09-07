<?php
require_once __DIR__.'/../../main/php/lib/databean/Key.php';
require_once __DIR__.'/../../main/php/lib/fielder/Fielder.php';
require_once __DIR__.'/../../main/php/lib/field/ColumnType.php';
class ExampleKey implements Key {

	private $i;

	public function __construct($i) {
		$this->i = $i;
	}

	/**
	 * @return Fielder
	 */
	public function getFielder() {
		return new KeyFielder();
	}

	public function getI() {
		return $this->i;
	}

}
class KeyFielder implements Fielder {

	public function getFields() {
		return [
			new BaseField('i', ColumnType::integer())
		];
	}

}
