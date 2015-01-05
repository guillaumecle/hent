<?php
namespace Hent\Databean;

abstract class BaseKey implements Key {

	public function __toString() {
		return implode($this->getFields());
	}

}
