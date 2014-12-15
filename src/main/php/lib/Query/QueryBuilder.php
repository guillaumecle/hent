<?php
namespace Hent\Query;

use ArrayIterator;
use CachingIterator;
use Hent\Databean\Databean;
use Hent\Databean\Fieldable;
use Hent\Databean\Key;
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

	// ************ SQL write op ******************* //

	/**
	 * @param Databean[] $databeans
	 * @return PreparedQuery
	 */
	public function getInsertMulti($databeans) {
		$databean = $databeans[0];
		$fieldCount = count($databean->getFields()) + count($databean->getKey()->getFields());
		$sql = 'insert into ' . $this->tableName . ' (';
		$value = ' (';
		$params = [];
		$keyFields = $databean->getKey()->getFields();
		$keyClass = new ReflectionClass($databean->getKey());
		/**
		 * @var $keyField Field
		 */
		$fieldIndex = 0;
		for (;$fieldIndex < count($keyFields); $fieldIndex++) {
			$keyField = $keyFields[$fieldIndex];
			$sql .= $keyField->getEscapedSqlName();
			$value .= '?';
			$prop = $keyClass->getProperty($keyField->getName());
			$prop->setAccessible(true);
			for ($i = 0; $i < count($databeans); $i++) {
				$params[$i * $fieldCount + $fieldIndex] = $keyField->serialize($prop->getValue($databeans[$i]->getKey()));
			}
			if ($fieldIndex < $fieldCount - 1) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$databeanFields = $databean->getFields();
		$databeanClass = new ReflectionClass($databean);
		for (;$fieldIndex < $fieldCount; $fieldIndex++) {
			$databeanField = $databeanFields[$fieldIndex - count($keyFields)];
			$sql .= $databeanField->getEscapedSqlName();
			$value .= '?';
			$prop = $databeanClass->getProperty($databeanField->getName());
			$prop->setAccessible(true);
			for ($i = 0; $i < count($databeans); $i++) {
				$params[$i * $fieldCount + $fieldIndex] = $databeanField->serialize($prop->getValue($databeans[$i]));
			}
			if ($fieldIndex < $fieldCount - 1) {
				$sql .= ', ';
				$value .= ', ';
			}
		}
		$value .= ')';
		$values = '';
		for ($i = 1; $i <= count($databeans); $i++) {
			$values .= $value;
			if ($i < count($databeans)) {
				$values .= ',';
			}
		}
		$sql .= ') value ' . $values;
		return new PreparedQuery($params, $sql);
	}

	/**
	 * @param $databean Databean
	 * @return PreparedQuery
	 */
	public function getUpdate($databean) {// TODO make this for multiple
		$sql = 'update ' . $this->tableName . ' set ';
		$iterator = new CachingIterator(new ArrayIterator($databean->getFields()));
		$class = new ReflectionClass(get_class($databean));
		$params = [];
		/**
		 * @var $field Field
		 */
		foreach ($iterator as $field) {
			$sql .= $field->getEscapedSqlName() . '=?';
			$prop = $class->getProperty($field->getName());
			$prop->setAccessible(true);
			$params[] = $field->serialize($prop->getValue($databean));
			if ($iterator->hasNext()) {
				$sql .= ', ';
			}
		}
		$where = $this->getWhereClause($databean->getKey());
		return new PreparedQuery(array_merge($params, $where->getData()), $sql . $where->getSql());
	}

	/**
	 * @param Fieldable[] $fieldables
	 * @return PreparedQuery
	 */
	public function getDeleteMulti($fieldables) {
		$sql = 'delete from ' . $this->tableName . ' where ';
		$disjunction = $this->getDisjunction($fieldables);
		return new PreparedQuery($disjunction->getData(), $sql . $disjunction->getSql());
	}

	// **************** SQL read op **************** //

	/**
	 * @param Key[] $keys
	 * @return PreparedQuery
	 */
	public function getSelectMulti($keys) {
		$sql = 'select * from ' . $this->tableName . ' where ';
		$disjunction = $this->getDisjunction($keys);
		return new PreparedQuery($disjunction->getData(), $sql . $disjunction->getSql());
	}

	/**
	 * @deprecated
	 * @param $fieldable Fieldable
	 * @return PreparedQuery
	 */
	private function getWhereClause($fieldable) {
		$sql = ' where ';
		$conjunction = $this->getConjunction($fieldable);
		return new PreparedQuery($conjunction->getData(), $sql . $conjunction->getSql());
	}

	// **************** SQL query utils **************** //

	/**
	 * @param Fieldable $fieldable
	 * @return PreparedQuery
	 */
	private function getConjunction($fieldable) {
		$sql = '';
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
	 * @param Fieldable[] $fieldables
	 * @return PreparedQuery
	 */
	private function getDisjunction($fieldables) {
		$sql = '';
		$params = [];
		$iterator = new CachingIterator(new ArrayIterator($fieldables));
		foreach ($iterator as $fieldable) {
			$conjunction = $this->getConjunction($fieldable);
			$sql .= '(' . $conjunction->getSql() . ')';
			$params = array_merge($params, $conjunction->getData());
			if ($iterator->hasNext()) {
				$sql .= 'or';
			}
		}
		return new PreparedQuery($params, $sql);
	}

}
