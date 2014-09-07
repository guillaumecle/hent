<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../fielder/Fielder.php';
require_once 'ColumnsKey.php';
class Columns implements DataBean {

	/**
	 * @var ColumnsKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $columnName;

	/**
	 * @var string
	 */
	private $columnType;

	/**
	 * @param $key ColumnsKey
	 */
	public function __construct($key) {
		$this->key = $key;
	}
	/**
	 * @return ColumnsKey
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Fielder
	 */
	public function getFielder() {
		return new ColumnsFielder();
	}

	/**
	 * @return string
	 */
	public function getColumnName() {
		return $this->columnName;
	}

	/**
	 * @return string
	 */
	public function getColumnType() {
		return $this->columnType;
	}

}
class ColumnsFielder implements Fielder {

	public function getFields() {
		return [
			(new BaseField('columnName', ColumnType::string()))->setSqlName('COLUMN_NAME'),
			(new BaseField('columnType', ColumnType::string()))->setSqlName('COLUMN_TYPE')
		];
	}

}