<?php
class DateField extends BaseField {

	public function __construct($name) {
		parent::__construct($name);
		$this->setType(ColumnType::datetime());
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function valueOf($dbString) {

	}

}