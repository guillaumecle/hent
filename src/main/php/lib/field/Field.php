<?php
interface Field {

	/**
	 * @return String
	 */
	public function getName();

	public function getSQLName();

	/**
	 * @return ColumnType
	 */
	public function getType();

	public function getValue();

}

