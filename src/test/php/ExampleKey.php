<?php
namespace HentTest;
use Hent\Databean\Key;
use Hent\Field\IntegerField;
use Hent\Field\StringField;

class ExampleKey implements Key {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @param int $id
	 * @param string $user
	 */
	public function __construct($id, $user) {
		$this->id = $id;
		$this->user = $user;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	public function getFields() {
		return [
			new IntegerField('id'),
			new StringField('user')
		];
	}

}
