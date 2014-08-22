<?php
interface Field {

	/**
	 * @return String
	 */
	public function getName();

	/**
	 * @return ColumnType
	 */
	public function getType();

}

