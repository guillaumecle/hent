<?php
namespace Hent\DataBean;
use Hent\Field\Field;

interface Fieldable {

	/**
	 * @return Field[]
	 */
	public function getFields();

}
