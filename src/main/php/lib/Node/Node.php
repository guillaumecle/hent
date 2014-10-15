<?php
namespace Hent\Node;

use Exception;
use Hent\Databean\Databean;
use Hent\Databean\Key;
use Hent\Databean\Lookup;
use Hent\Databean\LookupTool;
use Hent\Query\QueryBuilder;
use PDO;
use ReflectionClass;
use ReflectionProperty;

class Node {

	/**
	 * @var string
	 */
	private $sqlName;

	/**
	 * @var PDO
	 */
	private $co;

	/**
	 * @var Databean
	 */
	private $databean;
	/**
	 * @var QueryBuilder
	 */
	private $builder;

	/**
	 * @param string $databeanClass
	 * @param string $keyClass
	 * @param $sqlName string
	 */
	public function __construct($databeanClass, $keyClass, $sqlName = null) {
//		$this->databean = $databean;
		$databeanRC = new ReflectionClass($databeanClass);
		$this->databean = $databeanRC->newInstanceWithoutConstructor();
		$keyField = new ReflectionProperty($databeanClass, $this->databean->getKeyFieldName());
		$keyRC = new ReflectionClass($keyClass);
		$key = $keyRC->newInstanceWithoutConstructor();
		$keyField->setAccessible(true);
		$keyField->setValue($this->databean, $key);

		if (!empty($sqlName)) {
			$this->sqlName = $sqlName;
		} else {
			$this->sqlName = $databeanRC->getShortName();
		}
		$this->builder = new QueryBuilder($this);
	}

	/**
	 * @return string
	 */
	private function getName() {
		return $this->sqlName;
	}

	/**
	 * @return Databean
	 */
	public function getDatabean() {
		return $this->databean;
	}

	/**
	 * @return string
	 */
	public function getEscapedSqlName() {
		return '`' . $this->getSqlName() . '`';
	}

	/**
	 * @return string
	 */
	public function getSqlName() {
		return strtolower($this->getName());
	}

	/**
	 * @param $co PDO
	 */
	public function setConnection(PDO $co){
		$this->co = $co;
	}

	/**
	 * @param $databean Databean
	 */
	public function put(Databean $databean) {
		$key = $databean->getKey();
		$inDdDatabean = $this->get($key);
		if (isset($inDdDatabean)) {
			if(count($databean->getFields()) == 0){
				return;
			}
			$pQuery = $this->builder->getUpdate($databean);
			$st = $this->co->prepare($pQuery->getSql());
			$st->execute($pQuery->getData());
		} else {
			$pQuery = $this->builder->getInsert($databean);
			$st = $this->co->prepare($pQuery->getSql());
			$st->execute($pQuery->getData());
		}
	}

	/**
	 * Mainly for dev purpose
	 * @return Databean[]
	 */
	public function all() {
		$sql = 'select * from ' . $this->getEscapedSqlName();
		$st = $this->co->query($sql);
		$res = [];
		while ($rs = $st->fetch()) {
			$res[] = $this->databeanFromResultSet($rs);
		}
		return $res;
	}

	/**
	 * @param $key Key
	 * @return Databean|null
	 * @throws Exception
	 */
	public function get(Key $key) {
		$this->checkKey($key);
		$pQuery = $this->builder->getSelect($key);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
		if ($rs = $st->fetch()) {
			return $this->databeanFromResultSet($rs);
		}
		return null;
	}

	/**
	 * @param Lookup $lookup
	 * @return Databean[]
	 */
	public function lookup($lookup) {
		$this->checkLookup($lookup);
		$pQuery = $this->builder->getSelect($lookup);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
		$res = [];
		while ($rs = $st->fetch()) {
			$res[] = $this->databeanFromResultSet($rs);
		}
		return $res;
	}

	/**
	 * @param array $rs
	 * @return Databean
	 */
	private function databeanFromResultSet($rs) {
		$k = $this->getDatabean()->getKey();
		$kFields = $k->getFields();
		$kClass = new ReflectionClass(get_class($k));
		$key = $kClass->newInstanceWithoutConstructor();
		foreach($kFields as $field) {
			$fieldName = $field->getName();
			$prop = $kClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($key, $field->deserialize($rs[$field->getSqlName()]));
		}

		$d = $this->getDatabean();
		$dFields = $d->getFields();
		$dClass = new ReflectionClass(get_class($d));
		$databean = $dClass->newInstanceWithoutConstructor();
		$prop = $dClass->getProperty($this->getDatabean()->getKeyFieldName());
		$prop->setAccessible(true);
		$prop->setValue($databean, $key);
		foreach($dFields as $field) {
			$fieldName = $field->getName();
			$prop = $dClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($databean, $field->deserialize($rs[$field->getSqlName()]));
		}
		return $databean;
	}

	/**
	 * @param $key Key
	 */
	public function delete(Key $key) {
		$this->checkKey($key);
		$pq = $this->builder->getDelete($key);
		$ps = $this->co->prepare($pq->getSql());
		$ps->execute($pq->getData());
	}

	/**
	 * @param $key Key
	 * @throws Exception
	 */
	private function checkKey(Key $key) {
		if (!is_a($key, get_class($this->getDatabean()->getKey()))) {
			throw new Exception('Doesn\'t match key class (expected:' . get_class($this->getDatabean()->getKey()) . ', given: ' . get_class($key) . ')');
		}
	}

	/**
	 * @param $lookup Lookup
	 * @throws Exception
	 */
	private function checkLookup($lookup) {
		$indexNames = $this->getIndexNames();
		$indexName = LookupTool::getIndexName($lookup);
		if (!in_array($indexName, $indexNames)) {
			throw new Exception('The lookup "' . $indexName . '" is not register as an index for the Databean ' . get_class($this->getDatabean()));
		}
	}

	/**
	 * @return string[]
	 */
	private function getIndexNames() {
		if (!isset($this->indexNames)) {
			$this->indexNames = [];
			foreach ($this->getDatabean()->getIndexes() as $index) {
				$this->indexNames[] = LookupTool::getIndexName($index);
			}
		}
		return $this->indexNames;
	}

}
