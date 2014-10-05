<?php
namespace Hent\Field;

interface Field {

	/**
	 * @return String
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getSqlName();

	/**
	 * @return string
	 */
	public function getEscapedSqlName();

	/**
	 * @return ColumnType
	 */
	public function getType();

	/**
	 * @param string $dbString
	 * @return mixed
	 */
	public function deserialize($dbString);

	/**
	 * @param mixed $data
	 * @return string
	 */
	public function serialize($data);

}

