<?php
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
	public function valueOf($dbString);

}

