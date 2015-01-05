<?php
namespace Hent\Databean;

abstract class BaseLookup implements Lookup {

	public function __toString() {
		return implode($this->getFields());
	}

}
