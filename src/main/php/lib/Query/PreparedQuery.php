<?php
namespace Hent\Query;

class PreparedQuery {

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @param $data array
	 * @param $sql string
	 */
	function __construct($data, $sql) {
		$this->data = $data;
		$this->sql = $sql;
	}

	/**
	 * @return array
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