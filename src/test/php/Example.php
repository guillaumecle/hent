<?php
require_once __DIR__.'/../../main/php/lib/databean/DataBean.php';
require_once __DIR__.'/../../main/php/lib/fielder/Fielder.php';
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
	 */
	public function __construct($key) {
		$this->key = $key;
//		$this->val = $val;
	}

	/**
	 * @return ExampleKey
	 */
	public function getKey() {
		return $this->key;
	}
	/**
	 * @return Fielder
	 */
	public function getFielder() {
		return new ExampleFielder();
	}

	public function getVal() {
		return $this->val;
	}

}
class ExampleFielder implements  Fielder {

	/**
	 * @var $dataBean Example
	 * @return Field[]
	 */
	public function getFields($dataBean) {
		return [
//			new BaseField('i', ColumnType::integer(), $dataBean->getId()),
//			new BaseField('val', ColumnType::integer(), $dataBean->getVal())
		];
	}

}