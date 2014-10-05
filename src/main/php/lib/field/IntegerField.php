<?php
namespace Hent\Field;

class IntegerField extends BaseField {

	public function __construct($name, $size = 11) {
		parent::__construct($name, ColumnType::integer($size));
	}

	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data) {
		return strval($data);
	}

	/**
	 * @param int $dbString
	 * @return mixed
	 */
	public function deserialize($dbString) {
		return intval($dbString);
	}

}