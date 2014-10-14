<?php
namespace Hent\Databean;

interface Databean extends Fieldable {

	/**
	 * @return Key
	 */
	public function getKey();

	/**
	 * @return string
	 */
	public function getKeyFieldName();

	/**
	 * @return Lookup[]
	 */
	public function getIndexes();

}
