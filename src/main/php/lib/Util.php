<?php
namespace Hent;

class Util {

	/**
	 * @param string $string
	 */
	public static function println($string) {
		echo $string . PHP_EOL;
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

}
