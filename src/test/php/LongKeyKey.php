<?php
namespace HentTest;

use DateTime;
use Hent\Databean\BaseKey;
use Hent\Field\DateField;
use Hent\Field\Field;
use Hent\Field\IntegerField;
use Hent\Field\StringField;
use Hent\Util;

class LongKeyKey extends BaseKey {

	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var int
	 */
	private $timestamp;
	/**
	 * @var DateTime
	 */
	private $date;
	/**
	 * @var string
	 */
	private $aStringField;
	/**
	 * @var string
	 */
	private $anotherStringField;

	private function __construct($id, $timestamp, $date, $aStringField, $anotherStringField) {
		$this->id = $id;
		$this->timestamp = $timestamp;
		$this->date = $date;
		$this->aStringField = $aStringField;
		$this->anotherStringField = $anotherStringField;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			new IntegerField('timestamp'),
			new StringField('id'),
			new DateField('date'),
			new StringField('aStringField'),
			new StringField('anotherStringField'),
		];
	}

	public static function createRandom() {
		return new LongKeyKey(Util::generateRandomString(5), time(), new DateTime(), Util::generateRandomString(), Util::generateRandomString());
	}

}
