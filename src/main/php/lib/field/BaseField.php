<?php
require_once 'Field.php';
class BaseField implements Field {

	/**
	 * @var string
	 */
	private $fieldName;

	/**
	 * @var ColumnType
	 */
	private $type;

	/**
	 * @var string
	 */
	private $sqlName;

	/**
	 * @param $name string
	 * @param $type ColumnType
	 */
	public function __construct($name, ColumnType $type) {
		$this->fieldName = $name;
		$this->type = $type;
		$this->sqlName = $name;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->fieldName;
	}

	/**
	 * @return string
	 */
	public function getSqlName() {
		return $this->sqlName;
	}

	/**
	 * @return string
	 */
	public function getEscapedSqlName() {
		return '`' . $this->sqlName . '`';
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

	public function __toString() {
		return $this->fieldName . ' (' . $this->getEscapedSqlName() . ' ' . $this->type->getMySQLDeclaration() . ')';
	}

}
