<?php
namespace HentTest;

use Hent\Databean\BaseDatabean;
use Hent\Databean\Key;
use Hent\Databean\Lookup;
use Hent\Field\Field;
use Hent\Field\StringField;
use Hent\Util;

class LongKeyDatabean extends BaseDatabean {

	/**
	 * @var LongKeyKey
	 */
	private $key;
	/**
	 * @var string
	 */
	private $value;

	private function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}

	/**
	 * @return Key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [];
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			new StringField('value')
		];
	}

	public static function createRandom(){
		return new LongKeyDatabean(LongKeyKey::createRandom(), Util::generateRandomString());
	}

}
