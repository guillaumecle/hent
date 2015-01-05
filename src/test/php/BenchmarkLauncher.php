<?php
namespace HentTest;

use DateTime;
use stdClass;

require_once __DIR__.'/../../../vendor/autoload.php';

$mr = new ExampleRouter();
$lkd = new stdClass();
$lkd->node = $mr->longKeyDatabean;
$lkd->creationFct = function($i) {
	return LongKeyDatabean::createRandom();
};
$e = new stdClass();
$e->node = $mr->exampleNode;
$e->creationFct = function($i) {
	return new Example(new ExampleKey($i, 'me'), $i % 2, new DateTime());
};
$databeans = [
	$lkd,
	$e,
];
$ops = [
	Benchmark::OP_GET,
	Benchmark::OP_PUT,
];
$batchSizes = [
	1, 2, 4, 7, 12, 20, 33,
	54, 90, 148, 244, 403, 665,
	1096, 1808, 2980, 4914, 8103,
//			13359,
//			22026,
//			36315,
//			244,
];
new Benchmark($databeans, $ops, $batchSizes);