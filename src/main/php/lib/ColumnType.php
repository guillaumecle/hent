<?php
class ColumnType {

	/**
	 * @return ColumnType
	 */
	public static function integer() {
		new ColumnType("int(11)");
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