<?php
require_once 'BaseField.php';
class StringField extends BaseField {

	public function __construct($name, $size = 11) {
		parent::__construct($name);
		$this->setType(ColumnType::string($size));
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function valueOf($dbString) {
		return $dbString;
	}

}