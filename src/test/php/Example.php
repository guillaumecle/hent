<?php
require_once __DIR__.'/../../main/php/lib/databean/DataBean.php';
require_once __DIR__.'/../../main/php/lib/field/BaseField.php';
require_once __DIR__.'/../../main/php/lib/field/ColumnType.php';
require_once 'ExampleKey.php';
class Example implements DataBean {

	/**
	 * @var ExampleKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $val;

	/**
	 * @param $key ExampleKey
	 * @param $val int
	 */
	public function __construct($key, $val) {
		$this->key = $key;
		$this->val = $val;
	}

	/**
	 * @return ExampleKey
	 */
	public function getKey() {
		return $this->key;
	}

	public function getVal() {
		return $this->val;
	}

	public function setVal($val) {
		$this->val = $val;
	}

	public function getFields() {
		return [
			new BaseField('val', ColumnType::string())
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [];
	}

}
