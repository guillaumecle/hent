<?php
require_once __DIR__.'/../../main/php/lib/databean/DataBean.php';
require_once __DIR__.'/../../main/php/lib/field/DateField.php';
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
	 * @var DateTime
	 */
	private $date;

	/**
	 * @param $key ExampleKey
	 * @param $val int
	 * @param DateTime $date
	 */
	public function __construct($key, $val, $date) {
		$this->key = $key;
		$this->val = $val;
		$this->date = $date;
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

	/**
	 * @return DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	public function getFields() {
		return [
			new StringField('val'),
			new DateField('date')
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [];
	}

}
