<?php
namespace Hent\Range;

class Range {

	/**
	 * @var bool
	 */
	private $startInclusive;

	/**
	 * @var object
	 */
	private $start;

	/**
	 * @var bool
	 */
	private $endInclusive;

	/**
	 * @var object
	 */
	private $end;

	public function __construct($start = null, $end = null, $startInclusive = true, $endInclusive = false) {
		$this->start = $start;
		$this->startInclusive = $startInclusive;
		$this->end = $end;
		$this->endInclusive = $endInclusive;
	}

}
