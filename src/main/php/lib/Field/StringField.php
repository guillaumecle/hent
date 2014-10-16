<?php
namespace Hent\Field;

class StringField extends BaseField {

	public function __construct($name, $size = 255) {
		parent::__construct($name, ColumnType::string($size));
	}

	/**
	 * @param string $data
	 * @return string
	 */
	public function serialize($data) {
		return strval($data);
	}

	/**
	 * @param string $dbString
	 * @return string
	 */
	public function deserialize($dbString) {
		return strval($dbString);
	}

}
