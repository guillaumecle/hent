<?php
abstract class Node {

	/**
	 * @return String
	 */
	public abstract function getName();

	/**
     * @return Array
     */
    public abstract function getField();

	/**
	 * @return String
	 */
	public function getCreateScript() {
		$sql = 'create table ' . $this->getName() . '(';
		/**
		 * @var $field Field
		 */
		$iterator = new CachingIterator(new ArrayIterator($this->getField()));
		foreach ($iterator as $field) {
			$sql .= $field->getName() . ' ' . $field->getType()->getMySQLDeclaration();
			if ($iterator->hasNext()) {
				$sql .= ',';
			}
		}
		$sql .= ')';
		return $sql;
	}
}
