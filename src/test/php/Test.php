<?php
require_once __DIR__ . '/../../main/php/lib/util.php';
require_once __DIR__.'/../../main/php/lib/node/Node.php';
require_once __DIR__.'/../../main/php/lib/InfoSchema/InfoSchemaRouter.php';
require_once 'MyRouter.php';
require_once 'Example.php';
class Test extends PHPUnit_Framework_TestCase {

    public function testCreateScript() {
		$n = new Node(new Example(new ExampleKey(null)));
		//println($e->getCreateScript());
        $this->assertTrue(true);
    }

	public function testPut() {
		$mr = new MyRouter();
		$data = new Example(new ExampleKey(time()), 'a');
		$before = count($mr->exampleNode->all());
		$mr->exampleNode->put($data);
		$after = count($mr->exampleNode->all());
		$this->assertEquals($before + 1, $after);
	}

	public function testGet() {
		$mr = new MyRouter();
		$id = "1409655640";
		/**
		 * @var $key ExampleKey
		 */
		$key = new ExampleKey(1409655640);
		$d = $mr->exampleNode->get($key);
		$this->assertNotNull($d);
		$key = $d->getKey();
		$this->assertEquals($id, $key->getI());
	}

	public function testInfoSchema() {
		$r = new InfoSchemaRouter();
		$mr = new MyRouter();
//		$tab = $r->tables->all();
//		var_dump(count($tab));
		$d = $r->tables->get(new TablesKey($mr->getName(), $mr->exampleNode->getSqlName()));
	}
}

