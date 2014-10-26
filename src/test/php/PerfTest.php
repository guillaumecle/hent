<?php
namespace HentTest;

use DateTime;
use Hent\Databeans;
use Hent\Util;
use PHP_Timer;
use PHPUnit_Framework_TestCase;

class PerfTest extends PHPUnit_Framework_TestCase {

	const NB_INSERT = 8000;
	const MAX_TIME = 2;

	private static function displayResult($done, $time, $message) {
		Util::println($message . ' : ' . $done . ' ' . "\t" . '  ' . intval(1000 * $time) .' ms');
	}

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

	public function testPutSimple() {
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			foreach (self::$examples as $example) {
				self::$mr->exampleNode->put($example);
			}
			$time += PHP_Timer::stop();
			$done++;
			self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		} while ($time < self::MAX_TIME);
		self::displayResult($done, $time, 'put simple');
	}

	public function testPutMulti() {
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			self::$mr->exampleNode->putMulti(self::$examples);
			$time += PHP_Timer::stop();
			$done++;
			self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		} while ($time < self::MAX_TIME);
		self::displayResult($done, $time, 'put multip');
	}

	public function testGetSimple() {
		self::$mr->exampleNode->putMulti(self::$examples);
		$time = 0;
		$done = 0;
		do {
			$examples = [];
			PHP_Timer::start();
			foreach (Databeans::getKeys(self::$examples) as $key) {
				$examples[] = self::$mr->exampleNode->get($key);
			}
			$time += PHP_Timer::stop();
			$done++;
			foreach ($examples as $key => $example) {
				if ($example === null) {
					unset($examples[$key]);
				}
			}
			$this->assertEquals(self::NB_INSERT, count($examples));
		} while ($time < self::MAX_TIME);
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		self::displayResult($done, $time, 'get simple');
	}

	public function testGetMulti() {
		self::$mr->exampleNode->putMulti(self::$examples);
		$time = 0;
		$done = 0;
		do {
			PHP_Timer::start();
			$examples = self::$mr->exampleNode->getMulti(Databeans::getKeys(self::$examples));
			$time += PHP_Timer::stop();
			$done++;
			$this->assertEquals(self::NB_INSERT, count($examples));
		} while ($time < self::MAX_TIME);
		self::$mr->exampleNode->deleteMulti(Databeans::getKeys(self::$examples));
		self::displayResult($done, $time, 'get multip');
	}

}
