<?php
namespace Hent\Node;

use Hent\Query\QueryBuilder;
use Hent\Range\Range;
use Iterator;
use PDO;
use PDOStatement;

class ScanIterator implements Iterator {

	/**
	 * @var Node
	 */
	private $node;

	/**
	 * @var QueryBuilder
	 */
	private $queryBuilder;

	/**
	 * @var PDO
	 */
	private $connection;

	/**
	 * @var Range
	 */
	private $range;

	/**
	 * @var PDOStatement
	 */
	private $preparedStatement;

	private $position;

	function __construct(Node $node, QueryBuilder $queryBuilder, PDO $connection, Range $range) {
		$this->node = $node;
		$this->queryBuilder = $queryBuilder;
		$this->connection = $connection;
		$this->range = $range;
	}

	public function current() {
		return ;
	}

	public function next() {
		++$this->position;
		$resultSet = $this->preparedStatement->fetch();
		$this->databean = $this->node->databeanFromResultSet($resultSet);
		$this->lastKey = $this->databean->getKey();
	}

	public function key() {
		return $this->position;
	}

	public function valid() {
		return !$this->resultSet === false;
	}

	public function rewind() {// TODO prevent to rewind a second time ?
		$this->buildPreparedStatement();
		$this->position = 0;
	}

	private function buildPreparedStatement() {
		if (!empty($this->lastKey)) { //already started
			$startKey = $this->lastKey;
			$included = false;
			$preparedQuery = $this->queryBuilder->getSelectFrom($startKey, $included, BATCH_SIZE);
		} elseif (empty($this->range->getStart())) {//have no start range
			$preparedQuery = $this->queryBuilder->getSelect(BATCH_SIZE);
		} else {
			$startKey = $this->range->getStart();
			$included = $this->range->isStartInclusive();
			$preparedQuery = $this->queryBuilder->getSelectFrom($startKey, $included, BATCH_SIZE);
		}
		$preparedStatement = $this->connection->prepare($preparedQuery->getSql());
		$preparedStatement->execute($preparedQuery->getData());
		$this->preparedStatement = $preparedStatement;
	}

}
