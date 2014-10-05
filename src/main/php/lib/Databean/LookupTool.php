<?php
namespace Hent\DataBean;
use CachingIterator;
use ArrayIterator;

class LookupTool {

	/**
	 * @param Lookup $lookup
	 * @return string
	 */
	public static function getIndexName($lookup) {
		$indexName = 'index_';
		$fields = new CachingIterator(new ArrayIterator($lookup->getFields()));
		foreach ($fields as $field) {
			$indexName .= $field->getName();
			if ($fields->hasNext()) {
				$indexName .= '_';
			}
		}
		return $indexName;
	}

}
