<?php
require_once 'Fieldable.php';
interface DataBean extends Fieldable {

	/**
	 * @return Key
	 */
	public function getKey();

}
