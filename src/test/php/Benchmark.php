<?php
namespace HentTest;

use Hent\Databean\Databean;
use Hent\Databeans;
use Hent\Node\Node;
use Hent\Util;
use PHP_Timer;

//TODO put the output in a file
//TODO make the separator as setting
class Benchmark {

	const MAX_TIME = 4;
	const MIN_REPETITION = 2;

	const OP_PUT = 1;
	const OP_GET = 2;

	private static function displayResult($done, $time, $message) {
		Util::println($message . ' : ' . $done . ' ' . "\t" . '  ' . intval(1000 * $time) .' ms');
	}

	private static function displayTableResult($done, $time) {
		echo $done . "\t" . intval(1000 * $time);
	}

	/**
	 * @var int[]
	 */
	private $batchSizes;
	/**
	 * @var Node
	 */
	private $node;
	/**
	 * @var callable
	 */
	private $creationFct;
	/**
	 * @var Databean[]
	 */
	private $databeans;

	public function __construct(array $databeans, array $ops, $batchSizes) {
		$this->batchSizes = $batchSizes;
		foreach ($databeans as $databean) {
			foreach ($ops as $op) {
				$this->node = $databean->node;
				$this->creationFct = $databean->creationFct;
				if ($op === Benchmark::OP_GET) {
					Util::println($this->node->getSqlName() . ' gets');
					$this->opForEachSize(function() {$this->getSimple();}, function() {$this->getMulti();});
				} elseif ($op === Benchmark::OP_PUT) {
					Util::println($this->node->getSqlName() . ' puts');
					$this->opForEachSize(function() {$this->putSimple();}, function() {$this->putMulti();});
				}
			}
		}
	}

	public function opForEachSize(callable $simpleOp, callable $multiOp) {
		foreach($this->batchSizes as $batchSize) {
			$this->databeans = [];
			for ($i = 0; $i < $batchSize; $i++) {
				$this->databeans[] = call_user_func($this->creationFct, $i);
			}
			echo $batchSize . "\t";
			$simpleOp();
			echo "\t";
			$multiOp();
			echo PHP_EOL;
		}
	}

	public function putSimple() {
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			foreach ($this->databeans as $example) {
				$this->node->put($example);
			}
			$time += PHP_Timer::stop();
			$done++;
			$this->node->deleteMulti(Databeans::getKeys($this->databeans));
		} while ($done < self::MIN_REPETITION || $time < self::MAX_TIME);
		self::displayTableResult($done, $time);
	}

	public function putMulti() {
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			$this->node->putMulti($this->databeans);
			$time += PHP_Timer::stop();
			$done++;
			$this->node->deleteMulti(Databeans::getKeys($this->databeans));
		} while ($done < self::MIN_REPETITION || $time < self::MAX_TIME);
		self::displayTableResult($done, $time);
	}

	public function getSimple() {
		$this->node->putMulti($this->databeans);
		$time = 0;
		$done = 0;
		do {
			$examples = [];
			PHP_Timer::start();
			foreach (Databeans::getKeys($this->databeans) as $key) {
				$examples[] = $this->node->get($key);
			}
			$time += PHP_Timer::stop();
			$done++;
		} while ($done < self::MIN_REPETITION || $time < self::MAX_TIME);
		$this->node->deleteMulti(Databeans::getKeys($this->databeans));
		self::displayTableResult($done, $time);
	}

	public function getMulti() {
		$this->node->putMulti($this->databeans);
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			$this->node->getMulti(Databeans::getKeys($this->databeans));
			$time += PHP_Timer::stop();
			$done++;
		} while ($done < self::MIN_REPETITION || $time < self::MAX_TIME);
		$this->node->deleteMulti(Databeans::getKeys($this->databeans));
		self::displayTableResult($done, $time);
	}

}