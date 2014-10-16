<?php
namespace HentTest;

use DateTime;
use Hent\Databean\BaseDatabean;
use Hent\Databean\Lookup;
use Hent\Field\DateField;
use Hent\Field\Field;
use Hent\Field\StringField;

class Example extends BaseDatabean {

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
	 * @param ExampleKey $key
	 * @param string $val
	 * @param DateTime $date
	 */
	public function __construct(ExampleKey $key, $val, DateTime $date) {
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

	/**
	 * @return Field[]
	 */
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
		return [
			new TestLookup(null, null)
		];
	}

}
class TestLookup implements Lookup {

	/**
	 * @var string
	 */
	private $val;

	/**
	 * @var DateTime
	 */
	private $date;

	/**
	 * @param DateTime $date
	 * @param string $val
	 */
	function __construct(DateTime $date, $val) {
		$this->date = $date;
		$this->val = $val;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			new StringField('val'),
			new DateField('date')
		];
	}

}
