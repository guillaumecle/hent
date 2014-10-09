<?php
namespace Hent\Field;

class BooleanField extends BaseField {

	public function __construct($name) {
		parent::__construct($name, ColumnType::boolean());
	}

	/**
	 * @param string $dbString
	 * @return bool
	 */
	public function deserialize($dbString) {
		return boolval($dbString);
	}

	/**
	 * @param bool $data
	 * @return string
	 */
	public function serialize($data) {
		intval($data);
	}

}
