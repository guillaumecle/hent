<?php
namespace Hent\Databean;

abstract class BaseDatabean implements Databean {

	/**
	 * @return string
	 */
	public function getKeyFieldName() {
		return 'key';
	}

}
