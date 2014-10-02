<?php
require_once 'BaseField.php';
class TypedField extends  BaseField {

	/**
	 * @var callable
	 */
	private $serializer;

	/**
	 * @var callable
	 */
	private $deserializer;

	/**
	 * @param string $name
	 * @param ColumnType $type
	 * @param callable $serializer
	 * @param callable $deserializer
	 */
	public function __construct($name, $type, $serializer, $deserializer) {
		parent::__construct($name, $type);
		$this->serializer = $serializer;
		$this->deserializer = $deserializer;
	}

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function deserialize($dbString) {
		return call_user_func($this->deserializer, $dbString);
	}

	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data) {
		return call_user_func($this->serializer, $data);
	}

}
