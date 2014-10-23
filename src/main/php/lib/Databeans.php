<?php
namespace Hent;

use Hent\Databean\Databean;
use Hent\Databean\Key;

class Databeans {

	/**
	 * @param DataBean[] $databeans
	 * @return Key[]
	 */
	public static function getKeys($databeans) {
		$keys = [];
		foreach ($databeans as $databean) {
			$keys[] = $databean->getKey();
		}
		return $keys;
	}

}
