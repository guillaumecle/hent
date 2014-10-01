<?php
class ColumnType {

	/**
	 * @return ColumnType
	 */
	public static function integer() {
		return new ColumnType('int(11)', function($s) {return intval($s);});
	}

	/**
	 * @return ColumnType
	 */
	public static function string() {
		return new ColumnType('varchar(255)', function($s) {return $s;});
	}

	/**
	 * @var string
	 */
	private $sqlType;

	private $valueOfCallback;

	/**
	 * @param string $sqlType
	 * @param callable $valueOfCallback
	 */
	private function __construct($sqlType, $valueOfCallback) {
		$this->sqlType = $sqlType;
		$this->valueOfCallback = $valueOfCallback;
	}

	/**
	 * @return String
	 */
	public function getMySQLDeclaration() {
		return $this->sqlType;
	}

	/**
	 * @param string $string
	 * @return mixed
	 */
	public function valueOf($string) {
		return call_user_func($this->valueOfCallback, $string);
	}

}
