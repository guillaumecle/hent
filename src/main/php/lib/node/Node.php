<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../query/QueryBuilder.php';
class Node {
	/**
	 * @param $array array
	 * @return Node
	 */
	public static function fromInformationSchema($array) {
		//return new BaseNode();
	}

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
	 */
	public function __construct($dataBean) {
		$this->dataBean = $dataBean;
		$this->builder = new QueryBuilder($this);
	}

	/**
	 * @return String
	 */
	private function getName() {
		return get_class($this->dataBean);
	}

	/**
	 * @return DataBean
	 */
	public function getDataBean() {
		return $this->dataBean;
	}

	/**
	 * @return String
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
		$pQuery = $this->builder->getInsert($dataBean);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
	}

	/**
	 * @return DataBean[]
	 */
	public function all() {
		$sql = 'select * from ' . $this->getSqlName();
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
		if (get_class($key) != get_class($this->getDataBean()->getKey())) {
			throw new Exception("doesn't match key class");
		}
		$pQuery = $this->builder->getSelect($key);
		$st = $this->co->prepare($pQuery->getSql());
		$st->execute($pQuery->getData());
		if ($rs = $st->fetch()) {
			return $this->dataBeanFromResultSet($rs);
		}
		return null;
	}

	/**
	 * @param $rs
	 * @return DataBean
	 */
	private function dataBeanFromResultSet($rs) {
		$k = $this->getDataBean()->getKey();
		$kFields = $k->getFielder()->getFields($k);
		$kClass = new ReflectionClass(get_class($k));
		$key = $kClass->newInstanceWithoutConstructor();
		foreach($kFields as $field) {
			$fieldName = $field->getName();
			$prop = $kClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($key, $rs[$field->getSQLName()]);
		}

		$d = $this->getDataBean();
		$dFields = $d->getFielder()->getFields($d);
		$dClass = new ReflectionClass(get_class($d));
		$dataBean = $dClass->newInstanceWithoutConstructor();
		$prop = $dClass->getProperty('key');
		$prop->setAccessible(true);
		$prop->setValue($dataBean, $key);
		foreach($dFields as $field) {
			$fieldName = $field->getName();
			$prop = $dClass->getProperty($fieldName);
			$prop->setAccessible(true);
			$prop->setValue($dataBean, $rs[$field->getSQLName()]);
		}
		return $dataBean;
	}
}
