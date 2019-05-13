<?php

namespace BudTask;

use PHPUnit\Framework\TestCase;

class ToolsTest extends TestCase
{
	public function test_decodeBin()
	{
		$this->assertSame('foo', Tools::decodeBin('1100110 1101111 1101111'));
	}
}
