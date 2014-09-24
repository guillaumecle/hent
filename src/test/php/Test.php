<?php
require_once __DIR__ . '/../../main/php/lib/util.php';
require_once __DIR__.'/../../main/php/lib/node/Node.php';
require_once __DIR__.'/../../main/php/lib/InfoSchema/InfoSchemaRouter.php';
require_once 'MyRouter.php';
require_once 'Example.php';
class Test extends PHPUnit_Framework_TestCase {

	/**
	 * @var MyRouter
	 */
	private static $mr;

	public static function setUpBeforeClass() {
		self::$mr = new MyRouter();
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
		$data = new Example(new ExampleKey($id), 1);
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
		$val = 5;
		/**
		 * @var $key ExampleKey
		 */
		$key = new ExampleKey($id);
		self::$mr->exampleNode->put(new Example($key, $val));
		/**
		 * @var $d Example
		 */
		$d = self::$mr->exampleNode->get($key);
		$this->assertNotNull($d);
		$this->assertEquals($val, $d->getVal());
		$this->assertEquals($id, $d->getKey()->getId());

		$falseKey = new ExampleKey(123456);
		$d = self::$mr->exampleNode->get($falseKey);
		$this->assertNull($d);

		self::$mr->exampleNode->delete($key);
		$d = self::$mr->exampleNode->get($key);
		$this->assertNull($d);
	}

	public function testUpdate() {
		$id = time();
		$val = 152;
		$key = new ExampleKey($id);
		$data = new Example($key, $val);
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
		$r = new InfoSchemaRouter();
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

		$nb = count(self::$mr->exampleNode->getDataBean()->getFields()) + count(self::$mr->exampleNode->getDataBean()->getKey()->getFields());
		$this->assertEquals($nb, count($columns));

		$keyLookup = new KeyColumnsByTableAndName(self::$mr->getSqlName(), self::$mr->exampleNode->getSqlName(), 'PRIMARY');
		$keyCol = $r->keys->lookup($keyLookup);
		var_dump($keyCol);
		$this->assertEquals(count(self::$mr->exampleNode->getDataBean()->getKey()->getFields()), count($keyCol));
	}

}

