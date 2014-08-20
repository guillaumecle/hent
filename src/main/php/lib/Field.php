<?php
interface Field {

	/**
	 * @return String
	 */
	public function getName();

	/**
	 * @return ColumnType
	 */
	public function getType();

}
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
		$this->name;
	}

	/**
	 * @return ColumnType
	 */
	public function getType() {
		$this->type;
	}

}

