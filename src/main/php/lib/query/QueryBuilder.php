<?php
require_once __DIR__ . '/../util.php';
require_once 'PreparedQuery.php';
class QueryBuilder {

	private $tableName;

	/**
	 * @param $node Node
	 */
	public function __construct($node) {
		$this->tableName = $node->getSqlName();
	}

	/**
	 * @param $dataBean DataBean
	 * @return PreparedQuery
	 */
	public function getInsert(DataBean $dataBean) {
		$sql = 'insert into ' . $this->tableName . ' (';
		$value = ' (';
		$params = [];
		$kIterator = new CachingIterator(new ArrayIterator($dataBean->getKey()->getFielder()->getFields($dataBean->getKey())));
		/**
		 * @var $field Field
		 */
		foreach ($kIterator as $field) {
			$sql .= '`'.$field->getSQLName().'`';
			$value .= '?';
			$params[] = $field->getValue();
			if ($kIterator->hasNext()) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$dIterator = new CachingIterator(new ArrayIterator($dataBean->getFielder()->getFields($dataBean)));
		if ($dIterator->hasNext()) {
			$sql .= ', ';
			$value .= ', ';
		}
		foreach ($dIterator as $field) {
			$sql .= '`'.$field->getSQLName().'`';
			$value .= '?';
			$params[] = $field->getValue();
			if ($dIterator->hasNext()) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$sql .= ') value ' . $value . ')';
		return new PreparedQuery($params, $sql);
	}

	/**
	 * @param $key Key
	 * @return PreparedQuery
	 */
	public function getSelect(Key $key) {
		$sql = 'select * from ' . $this->tableName . ' where ';
		$params = [];
		$iterator = new CachingIterator(new ArrayIterator($key->getFielder()->getFields($key)));
		/**
		 * @var $field Field
		 */
		foreach ($iterator as $field) {
			$sql .= '`'.$field->getSQLName() . '`=?';
			$params[] = $field->getValue();
			if ($iterator->hasNext()) {
				$sql .= ' and ';
			}
		}
		return new PreparedQuery($params, $sql);
	}

}
