<?php
namespace Hent\Field;

class ColumnType {

	/**
	 * @param int $size
	 * @return ColumnType
	 */
	public static function integer($size) {
		return new ColumnType('int(' . $size . ')');
	}

	/**
	 * @param int $size
	 * @return ColumnType
	 */
	public static function string($size) {
		return new ColumnType('varchar(' . $size . ')');
	}

	/**
	 * @return ColumnType
	 */
	public static function datetime() {
		return new ColumnType('datetime');
	}

	/**
	 * @var string
	 */
	private $sqlType;

	/**
	 * @param string $sqlType
	 */
	private function __construct($sqlType) {
		$this->sqlType = $sqlType;
	}

	/**
	 * @return String
	 */
	public function getMySQLDeclaration() {
		return $this->sqlType;
	}

}
