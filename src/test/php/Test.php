<?php
namespace HentTest;

use DateTime;
use Hent\Databeans;
use Hent\InfoSchema\Columns;
use Hent\InfoSchema\ColumnsByTableLookup;
use Hent\InfoSchema\IndexesByTable;
use Hent\InfoSchema\InfoSchemaRouter;
use Hent\InfoSchema\KeyColumnsByTableAndName;
use Hent\InfoSchema\Tables;
use Hent\InfoSchema\TablesBySchemaLookup;
use Hent\Util;
use PHP_Timer;
use PHPUnit_Framework_TestCase;

class Test extends PHPUnit_Framework_TestCase {

	const NB_INSERT = 200;

	/**
	 * @var ExampleRouter
	 */
	private static $mr;

	/**
	 * @var Example[]
	 */
	private static $examples;

	public static function setUpBeforeClass() {
		self::$mr = new ExampleRouter();
		self::$examples = [];
		for ($i = 0; $i < self::NB_INSERT; $i++) {
			self::$examples[] = new Example(new ExampleKey($i, 'me'), $i % 2, new DateTime());
		}
	}

	public function testAllAndDelete() {
		$all = self::$mr->exampleNode->all();
		foreach ($all as $databean) {
			self::$mr->exampleNode->delete($databean->getKey());
		}
		$count = count(self::$mr->exampleNode->all());
		$this->assertEquals(0, $count);
	}

	public function testPutAndDelete() {
		$id = time();
		$data = new Example(new ExampleKey($id, 'me'), 1, new DateTime());
		$before = count(self::$mr->exampleNode->all());

		self::$mr->exampleNode->put($data);
		$after = count(self::$mr->exampleNode->all());
		$this->assertEquals($before + 1, $after);

		self::$mr->exampleNode->delete($data->getKey());
		$after = count(self::$mr->exampleNode->all());
		$this->assertEquals($before, $after);
	}

	public function testGet() {
		$id = time();
		$val = 'hey';

		/**
		 * @var $key ExampleKey
		 */
		$key = new ExampleKey($id, 'me');
		self::$mr->exampleNode->put(new Example($key, $val, new DateTime()));
		/**
		 * @var $d Example
		 */
		$d = self::$mr->exampleNode->get($key);
		$this->assertNotNull($d);
		$this->assertSame($val, $d->getVal());
		$this->assertSame($id, $d->getKey()->getId());

		$falseKey = new ExampleKey(123456, 'me');
		$d = self::$mr->exampleNode->get($falseKey);
		$this->assertNull($d);

		self::$mr->exampleNode->delete($key);
		$d = self::$mr->exampleNode->get($key);
		$this->assertNull($d);
	}

	public function testUpdate() {
		$id = time();
		$val = 152;
		$key = new ExampleKey($id, 'me');
		$data = new Example($key, $val, new DateTime());
		self::$mr->exampleNode->put($data);

		/**
		 * @var $fromDb Example
		 */
		$fromDb = self::$mr->exampleNode->get($key);
		$this->assertEquals($val, $fromDb->getVal());

		$newVal = 963;
		$data->setVal($newVal);
		self::$mr->exampleNode->put($data);
		$fromDb = self::$mr->exampleNode->get($key);
		$this->assertEquals($newVal, $fromDb->getVal());

		self::$mr->exampleNode->delete($key);
	}

	public function testInfoSchema() {
		$r = new InfoSchemaRouter(new ExampleRouterConfig());
		/**
		 * @var $tables Tables[]
		 */
		$tables = $r->tables->lookup(new TablesBySchemaLookup(self::$mr->getSqlName()));
		$this->assertEquals(count(self::$mr->getNodes()), count($tables));

		$this->assertEquals(self::$mr->getSqlName(), $tables[0]->getSchema());

		$lookup = new ColumnsByTableLookup(self::$mr->getSqlName(), self::$mr->exampleNode->getSqlName());
		/**
		 * @var $columns Columns[]
		 */
		$columns = $r->columns->lookup($lookup);
		$this->assertNotNull($columns);
		$this->assertEquals(self::$mr->getSqlName(), $columns[0]->getSchema());

		$nb = count(self::$mr->exampleNode->getDatabean()->getFields()) + count(self::$mr->exampleNode->getDatabean()->getKey()->getFields());
		$this->assertEquals($nb, count($columns));

		$keyLookup = new KeyColumnsByTableAndName(self::$mr->getSqlName(), self::$mr->exampleNode->getSqlName(), 'PRIMARY');
		$keyCol = $r->keys->lookup($keyLookup);
		$this->assertEquals(count(self::$mr->exampleNode->getDatabean()->getKey()->getFields()), count($keyCol));

		$indexesLookup = new IndexesByTable(self::$mr->getSqlName(), self::$mr->exampleNode->getSqlName());
		$indexesCols = $r->indexes->lookup($indexesLookup);
	}

	public function testFields() {
		$id = time();
		$val = 'hey';
		$date = new DateTime();

		/**
		 * @var $key ExampleKey
		 */
		$key = new ExampleKey($id, 'me');
		self::$mr->exampleNode->put(new Example($key, $val, new DateTime()));
		/**
		 * @var $d Example
		 */
		$d = self::$mr->exampleNode->get($key);
		$this->assertEquals($date, $d->getDate());
		self::$mr->exampleNode->delete($key);
	}

	public function testPerfPutSimple() {
		PHP_Timer::start();
		foreach(self::$examples as $example) {
			self::$mr->exampleNode->put($example);
		}
		$time = PHP_Timer::stop();
		Util::println('put simple : ' . PHP_Timer::secondsToTimeString($time));
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
	}

	public function testPerfPutMulti() {
		PHP_Timer::start();
		self::$mr->exampleNode->putMulti(self::$examples);
		$time = PHP_Timer::stop();
		Util::println('put multi : ' . PHP_Timer::secondsToTimeString($time));
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
	}

	public function testPerfGetSimple() {
		self::$mr->exampleNode->putMulti(self::$examples);
		$examples = [];
		PHP_Timer::start();
		foreach (Databeans::getKeys(self::$examples) as $key) {
			$examples[] = self::$mr->exampleNode->get($key);
		}
		$time = PHP_Timer::stop();
		foreach ($examples as $key => $example) {
			if ($example === null) {
				unset($examples[$key]);
			}
		}
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		$this->assertEquals(self::NB_INSERT, count($examples));
		Util::println('get simple : ' . PHP_Timer::secondsToTimeString($time));
	}

	public function testPerfGetMulti() {
		self::$mr->exampleNode->putMulti(self::$examples);
		PHP_Timer::start();
		$examples = self::$mr->exampleNode->getMulti(Databeans::getKeys(self::$examples));
		$time = PHP_Timer::stop();
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		$this->assertEquals(self::NB_INSERT, count($examples));
		Util::println('get multi : ' . PHP_Timer::secondsToTimeString($time));
	}

}
