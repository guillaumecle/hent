<?php
namespace Hent\Databean;
use CachingIterator;
use ArrayIterator;
use Hent\Field\Field;

class LookupTool {

	/**
	 * @param Lookup $lookup
	 * @return string
	 */
	public static function getIndexName($lookup) {
		$indexName = 'index_';
		$fields = new CachingIterator(new ArrayIterator($lookup->getFields()));
		/**
		 * @var Field $field
		 */
		foreach ($fields as $field) {
			$indexName .= $field->getName();
			if ($fields->hasNext()) {
				$indexName .= '_';
			}
		}
		return $indexName;
	}

}
