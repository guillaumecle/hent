<?php
require_once 'BaseField.php';
require_once 'ColumnType.php';
class StringField extends BaseField {

	public function __construct($name, $size = 11) {
		parent::__construct($name);
		$this->setType(ColumnType::string($size));
	}

	/**
	 * @param string $data
	 * @return string
	 */
	public function serialize($data) {
		return $data;
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function deserialize($dbString) {
		return $dbString;
	}

}
