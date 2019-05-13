<?php

namespace BudTask\Exceptions;

use Exception;

class JsonException extends Exception
{
	public function __construct()
	{
		parent::__construct(sprintf('Json decode error: "%s"', json_last_error_msg()));
	}
}
