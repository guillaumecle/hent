<?php
class BaseField implements Field {

	private $name;
	private $type;

	/**
	 * @param $name String
	 * @param $type ColumnType
	 */
	public function __construct($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return ColumnType
	 */
	public function getType() {
		return $this->type;
	}

	public function __toString() {
		return $this->name;
	}

}
