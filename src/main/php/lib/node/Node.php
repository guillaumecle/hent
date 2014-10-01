<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../query/QueryBuilder.php';
require_once __DIR__.'/../databean/LookupTool.php';
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
	 * @var DataBean
	 */
	private $dataBean;
	/**
	 * @var QueryBuilder
	 */
	private $builder;

	/**
	 * @param $dataBean DataBean
	 * @param $sqlName string
	 */
	public function __construct($dataBean, $sqlName = null) {
		$this->dataBean = $dataBean;
		if (!empty($sqlName)) {
			$this->sqlName = $sqlName;
		}
		$this->builder = new QueryBuilder($this);
	}

	/**
	 * @return string
	 */
	private function getName() {
		if (isset($this->sqlName)) {
			return $this->sqlName;
		}
		return get_class($this->dataBean);
	}

	/**
	 * @return DataBean
	 */
	public function getDataBean() {
		return $this->dataBean;
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
	 * @param $dataBean DataBean
	 */
	public function put(DataBean $dataBean) {
		$key = $dataBean->getKey();
		$inDdDataBean = $this->get($key);
		if (isset($inDdDataBean)) {
			$pQuery = $this->builder->getUpdate($dataBean);
			$st = $this->co->prepare($pQuery->getSql());
			$st->execute($pQuery->getData());
		} else {
			$pQuery = $this->builder->getInsert($dataBean);
			$st = $this->co->prepare($pQuery->getSql());
			$st->execute($pQuery->getData());
		}
	}

	/**
	 * Mainly for dev purpose
	 * @return DataBean[]
	 */
	public function all() {
		$sql = 'select * from ' . $this->getEscapedSqlName();
		$st = $this->co->query($sql);
		$res = [];
		while ($rs = $st->fetch()) {
			$res[] = $this->dataBeanFromResultSet($rs);
		}
		return $res;
	}

	/**
	 * @param $key Key
	 * @return DataBean|null
	 * @throws Exception
	 */
	public function get(Key $key) {
		$this->checkKey($key);
		$pQuery = $this->builder->getSelect($key);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
		if ($rs = $st->fetch()) {
			return $this->dataBeanFromResultSet($rs);
		}
		return null;
	}

	/**
	 * @param Lookup $lookup
	 * @return DataBean[]
	 */
	public function lookup(Lookup $lookup) {
		$this->checkLookup($lookup);
		$pQuery = $this->builder->getSelect($lookup);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
		$res = [];
		while ($rs = $st->fetch()) {
			$res[] = $this->dataBeanFromResultSet($rs);
		}
		return $res;
	}

	/**
	 * @param $rs
	 * @return DataBean
	 */
	private function dataBeanFromResultSet($rs) {
		$k = $this->getDataBean()->getKey();
		$kFields = $k->getFields();
		$kClass = new ReflectionClass(get_class($k));
		$key = $kClass->newInstanceWithoutConstructor();
		foreach($kFields as $field) {
			$fieldName = $field->getName();
			$prop = $kClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($key, $field->valueOf($rs[$field->getSqlName()]));
		}

		$d = $this->getDataBean();
		$dFields = $d->getFields();
		$dClass = new ReflectionClass(get_class($d));
		$dataBean = $dClass->newInstanceWithoutConstructor();
		$prop = $dClass->getProperty('key');
		$prop->setAccessible(true);
		$prop->setValue($dataBean, $key);
		foreach($dFields as $field) {
			$fieldName = $field->getName();
			$prop = $dClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($dataBean, $rs[$field->getSqlName()]);
		}
		return $dataBean;
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
		if (!is_a($key, get_class($this->getDataBean()->getKey()))) {
			throw new Exception('Doesn\'t match key class (expected:' . get_class($this->getDataBean()->getKey()) . ', given: ' . get_class($key) . ')');
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
			throw new Exception('The lookup "' . $indexName . '" is not register as an index for the databean ' . get_class($this->getDataBean()));
		}
	}

	/**
	 * @return string[]
	 */
	private function getIndexNames() {
		if (!isset($this->indexNames)) {
			$this->indexNames = [];
			foreach ($this->getDataBean()->getIndexes() as $index) {
				$this->indexNames[] = LookupTool::getIndexName($index);
			}
		}
		return $this->indexNames;
	}

}
