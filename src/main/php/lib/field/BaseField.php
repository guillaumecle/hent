<?php
require_once 'Field.php';
class BaseField implements Field {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var ColumnType
	 */
	private $type;

	private $value;

	/**
	 * @var string
	 */
	private $sqlName;

	/**
	 * @param $name string
	 * @param $type ColumnType
	 * @param $value
	 */
	public function __construct($name, ColumnType $type, $value) {
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->sqlName = $name;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getSqlName() {
		return $this->sqlName;
	}

	/**
	 * @param string $sqlName
	 * @return $this BaseField
	 */
	public function setSqlName($sqlName) {
		$this->sqlName = $sqlName;
		return $this;
	}

	/**
	 * @return ColumnType
	 */
	public function getType() {
		return $this->type;
	}

	public function getValue() {
		return $this->value;
	}

	public function __toString() {
		return $this->name . ' (' . $this->type->getMySQLDeclaration() . ') : ' . $this->value;
	}

}
