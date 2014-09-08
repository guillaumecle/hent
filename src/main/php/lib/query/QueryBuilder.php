<?php
require_once __DIR__ . '/../util.php';
require_once 'PreparedQuery.php';
class QueryBuilder {

	private $tableName;

	/**
	 * @param $node Node
	 */
	public function __construct($node) {
		$this->tableName = $node->getEscapedSqlName();
	}

	/**
	 * @param $dataBean DataBean
	 * @return PreparedQuery
	 */
	public function getInsert(DataBean $dataBean) {
		$sql = 'insert into ' . $this->tableName . ' (';
		$value = ' (';
		$params = [];
		$kIterator = new CachingIterator(new ArrayIterator($dataBean->getKey()->getFields()));
		$class = new ReflectionClass(get_class($dataBean->getKey()));
		/**
		 * @var $field Field
		 */
		foreach ($kIterator as $field) {
			$sql .= $field->getEscapedSqlName();
			$value .= '?';
			$prop = $class->getProperty($field->getName());
			$prop->setAccessible(true);
			$params[] = $prop->getValue($dataBean->getKey());
			if ($kIterator->hasNext()) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$dIterator = new CachingIterator(new ArrayIterator($dataBean->getFields()));
		$class = new ReflectionClass(get_class($dataBean));
		if ($dIterator->hasNext()) {
			$sql .= ', ';
			$value .= ', ';
		}
		foreach ($dIterator as $field) {
			$sql .= $field->getEscapedSqlName();
			$value .= '?';
			$prop = $class->getProperty($field->getName());
			$prop->setAccessible(true);
			$params[] = $prop->getValue($dataBean);
			if ($dIterator->hasNext()) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$sql .= ') value ' . $value . ')';
		return new PreparedQuery($params, $sql);
	}

	/**
	 * @param $fieldable Fieldable
	 * @return PreparedQuery
	 */
	public function getSelect(Fieldable $fieldable) {
		$sql = 'select * from ' . $this->tableName;
		$pq = $this->getWhereClause($fieldable);
		return new PreparedQuery($pq->getData(), $sql . $pq->getSql());
	}

	public function getDelete($key) {
		$sql = 'delete from ' . $this->tableName;
		$pq = $this->getWhereClause($key);
		return new PreparedQuery($pq->getData(), $sql . $pq->getSql());
	}

	/**
	 * @param $fieldable Fieldable
	 * @return PreparedQuery
	 */
	private function getWhereClause($fieldable) {
		$sql = ' where ';
		$params = [];
		$iterator = new CachingIterator(new ArrayIterator($fieldable->getFields()));
		$class = new ReflectionClass(get_class($fieldable));
		/**
		 * @var $field Field
		 */
		foreach ($iterator as $field) {
			$sql .= $field->getEscapedSqlName() . '=?';
			$prop = $class->getProperty($field->getName());
			$prop->setAccessible(true);
			$params[] = $prop->getValue($fieldable);
			if ($iterator->hasNext()) {
				$sql .= ' and ';
			}
		}
		return new PreparedQuery($params, $sql);
	}

}
