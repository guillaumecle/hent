<?php
namespace Hent\Query;
use ArrayIterator;
use CachingIterator;
use Hent\DataBean\Databean;
use Hent\DataBean\Fieldable;
use Hent\Field\Field;
use Hent\Node\Node;
use ReflectionClass;

class QueryBuilder {

	private $tableName;

	/**
	 * @param $node Node
	 */
	public function __construct($node) {
		$this->tableName = $node->getEscapedSqlName();
	}

	/**
	 * @param $dataBean Databean
	 * @return PreparedQuery
	 */
	public function getInsert(Databean $dataBean) {
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
			$params[] = $field->serialize($prop->getValue($dataBean->getKey()));
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
			$params[] = $field->serialize($prop->getValue($dataBean));
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
			$params[] = $field->serialize($prop->getValue($fieldable));
			if ($iterator->hasNext()) {
				$sql .= ' and ';
			}
		}
		return new PreparedQuery($params, $sql);
	}

	/**
	 * @param $dataBean Databean
	 * @return PreparedQuery
	 */
	public function getUpdate($dataBean) {
		$sql = 'update ' . $this->tableName . ' set ';
		$iterator = new CachingIterator(new ArrayIterator($dataBean->getFields()));
		$class = new ReflectionClass(get_class($dataBean));
		$params = [];
		/**
		 * @var $field Field
		 */
		foreach ($iterator as $field) {
			$sql .= $field->getEscapedSqlName() . '=?';
			$prop = $class->getProperty($field->getName());
			$prop->setAccessible(true);
			$params[] = $field->serialize($prop->getValue($dataBean));
			if ($iterator->hasNext()) {
				$sql .= ', ';
			}
		}
		$where = $this->getWhereClause($dataBean->getKey());
		return new PreparedQuery(array_merge($params, $where->getData()), $sql . $where->getSql());
	}

}