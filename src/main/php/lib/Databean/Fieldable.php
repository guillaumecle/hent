<?php
namespace Hent\Databean;
use Hent\Field\Field;

interface Fieldable {

	/**
	 * @return Field[]
	 */
	public function getFields();

}
