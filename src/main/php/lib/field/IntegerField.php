<?php
class IntegerField extends BaseField {

	public function __construct($name, $size = 11) {
		parent::__construct($name);
		$this->setType(ColumnType::integer($size));
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function valueOf($dbString) {
		return intval($dbString);
	}

}