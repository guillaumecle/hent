<?php
namespace Hent\Field;
abstract class BaseField implements Field {

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
	 * @param ColumnType $type
	 */
	public function __construct($name, $type) {
		$this->fieldName = $name;
		$this->sqlName = $name;
		$this->type = $type;
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
	 * @return BaseField $this
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

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->fieldName . ' (' . $this->getEscapedSqlName() . ' ' . $this->type->getMySQLDeclaration() . ')';
	}

}
