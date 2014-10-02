<?php
require_once 'BaseField.php';
class TypedField extends  BaseField {

	private $deserializerCallback;

	/**
	 * @param string $name
	 * @param ColumnType $type
	 * @param callback $deserializerCallback
	 */
	public function __construct($name, $type, $deserializerCallback) {
		parent::__construct($name);
		$this->setType($type);
		$this->deserializerCallback = $deserializerCallback;
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function valueOf($dbString) {
		return call_user_func($this->deserializerCallback, $dbString);
	}

}