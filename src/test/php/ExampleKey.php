<?php
require_once __DIR__.'/../../main/php/lib/databean/Key.php';
require_once __DIR__.'/../../main/php/lib/field/IntegerField.php';
class ExampleKey implements Key {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @param int $id
	 */
	public function __construct($id) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	public function getFields() {
		return [
			new IntegerField('id')
		];
	}

}
