<?php
interface Fielder {

	/**
	 * @var $key Key|DataBean
	 * @return Field[]
	 */
	public function getFields($key);

}
