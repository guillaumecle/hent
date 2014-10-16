<?php
namespace Hent\Query;

class PreparedQuery {

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @var string[]
	 */
	private $data;

	/**
	 * @param string[] $data
	 * @param string $sql
	 */
	function __construct($data, $sql) {
		$this->data = $data;
		$this->sql = $sql;
	}

	/**
	 * @return string[]
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getSql() {
		return $this->sql;
	}

}