<?php
namespace Hent\DataBean;

interface Databean extends Fieldable {

	/**
	 * @return Key
	 */
	public function getKey();

	/**
	 * @return Lookup[]
	 */
	public function getIndexes();
}
