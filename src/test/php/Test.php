<?php
require_once '../../main/php/lib/Node.php';
require_once '../../main/php/lib/Field.php';
require_once '../../main/php/lib/BaseField.php';
require_once '../../main/php/lib/ColumnType.php';
class Test extends PHPUnit_Framework_TestCase {

    public function test() {
		$e = new ExampleNode();
		echo $e->getCreateScript();
        $this->assertTrue(true);
    }

}
class ExampleNode extends Node {

	/**
	 * @return String
	 */
	public function getName() {
		return "Example";
	}
	/**
	 * @return Array
	 */
	public function getField() {
		return [
			new BaseField("id", ColumnType::integer()),
			new BaseField("val", ColumnType::integer())
		];
	}

}

