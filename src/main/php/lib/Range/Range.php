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

	public function __construct($start, $end = null, $startInclusive = true, $endInclusive = false) {
		$this->start = $start;
		$this->startInclusive = $startInclusive;
		$this->end = $end;
		$this->endInclusive = $endInclusive;
	}

	/**
	 * @return object
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * @return boolean
	 */
	public function isStartInclusive() {
		return $this->startInclusive;
	}

	/**
	 * @return object
	 */
	public function getEnd() {
		return $this->end;
	}

	/**
	 * @return boolean
	 */
	public function isEndInclusive() {
		return $this->endInclusive;
	}

}
