<?php
class DateField extends BaseField {

	public function __construct($name) {
		parent::__construct($name);
		$this->setType(ColumnType::datetime());
	}

	/**
	 * @param DateTime $data
	 * @return string
	 */
	public function serialize($data) {
		return $data->format('Y-m-d H:i:s');
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function deserialize($dbString) {
		return new DateTime($dbString);
	}

}