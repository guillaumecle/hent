<?php
namespace Hent\Node;

use Hent\Range\Range;

interface SortedNode {

	function scan(Range $range = null);

}
