<?php
class ColumnType {

	/**
	 * @return ColumnType
	 */
	public static function integer() {
		return new ColumnType("int(11)");
	}

	/**
	 * @return ColumnType
	 */
	public static function string() {
		return new ColumnType('varchar(255)');
	}

	private $sqlType;

	/**
	 * @param $sqlType String
	 */
	private function __construct($sqlType) {
		$this->sqlType = $sqlType;
	}

	/**
	 * @return String
	 */
	public function getMySQLDeclaration(){
		return $this->sqlType;
	}

}
