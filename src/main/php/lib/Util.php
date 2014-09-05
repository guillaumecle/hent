<?php
/**
 * @param $string
 */
function println($string) {
	echo $string . PHP_EOL;
}

/**
 * @return String
 */
function getCreateScript() {
	$sql = 'create table ' . $this->getName() . '(';
	$iterator = new CachingIterator(new ArrayIterator($this->getField()));
	/**
	 * @var $field Field
	 */
	foreach ($iterator as $field) {
		$sql .= "\n\t" . $field->getName() . ' ' . $field->getType()->getMySQLDeclaration();
		if ($iterator->hasNext()) {
			$sql .= ',';
		}
	}
	$sql .= "\n" . ')';
	return $sql;
}
