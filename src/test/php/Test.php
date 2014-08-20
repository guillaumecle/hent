<?php
require_once '../../main/php/lib/Node.php';
require_once '../../main/php/lib/Field.php';
require_once '../../main/php/lib/ColumnType.php';
class Test extends PHPUnit_Framework_TestCase {

    public function test() {
		$e = new ExampleNode();
		$e->getField();
        $this->assertTrue(true);
    }

}
class ExampleNode implements Node {

	/**
	 * @return Array
	 */
	public function getField() {
		return [
			new BaseField("id", ColumnType::integer())
		];
	}
}

