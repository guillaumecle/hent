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

	public function testPutAndDelete() {
		$data = new Example(new ExampleKey(time()), 'a');
		$before = count(self::$mr->exampleNode->all());
		self::$mr->exampleNode->put($data);
		$after = count(self::$mr->exampleNode->all());
		$this->assertEquals($before + 1, $after);
		self::$mr->exampleNode->delete($data->getKey());
		$after = count(self::$mr->exampleNode->all());
		$this->assertEquals($before, $after);
	}

	public function testGet() {
		$id = "1409655640";
		/**
		 * @var $key ExampleKey
		 */
		$key = new ExampleKey(1409655640);
		self::$mr->exampleNode->put(new Example($key, 5));
		$d = self::$mr->exampleNode->get($key);
		$this->assertNotNull($d);
		$key = $d->getKey();
		$this->assertEquals($id, $key->getId());
		self::$mr->exampleNode->delete($key);

		$key = new ExampleKey(12);
		$d = self::$mr->exampleNode->get($key);
		$this->assertNull($d);
	}

	public function testInfoSchema() {
		$r = new InfoSchemaRouter();
		/**
		 * @var $d Tables
		 */
		$d = $r->tables->get(new TablesKey(self::$mr->getName(), self::$mr->exampleNode->getName()));
		$this->assertNotNull($d);
		$this->assertEquals('InnoDB', $d->getEngine());
		/**
		 * @var Columns
		 */
//		$col = $r->columns->get(new ColumnsKey($mr->getName(), $mr->exampleNode->getName()));
//		$this->assertNotNull($col);

		$lookup = new ColumnsByTableLookup(self::$mr->getName(), self::$mr->exampleNode->getName());
		$ds = $r->columns->lookup($lookup);
		$this->assertNotNull($ds);
		$this->assertEquals(2, count($ds));
	}
}

