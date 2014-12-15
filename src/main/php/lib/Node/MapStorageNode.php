<?php
namespace Hent\Node;

use Hent\Databean\Databean;
use Hent\Databean\Key;

interface MapStorageNode {

	/**
	 * @param Key $key
	 * @return Databean
	 */
	function get(Key $key);

	/**
	 * @param Key[] $keys
	 * @return Databean[]
	 */
	function getMulti($keys);

	/**
	 * @param Databean $databean
	 */
	function put(Databean $databean);

	/**
	 * @param Databean[] $databeans
	 */
	function putMulti($databeans);

	/**
	 * @param Key $key
	 */
	function delete(Key $key);

	/**
	 * @param Key[] $keys
	 */
	function deleteMulti($keys);

}
