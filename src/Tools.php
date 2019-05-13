<?php

namespace BudTask;

abstract class Tools
{
	public static function decodeBin(string $bin): string
	{
		$chars = explode(' ', $bin);

		$result = [];
		foreach ($chars as $char) {
			$result[] = chr(bindec($char));
		}

		return implode('', $result);
	}
}
