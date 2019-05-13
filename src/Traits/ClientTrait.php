<?php

namespace BudTask\Traits;

use BudTask\Interfaces;

trait ClientTrait
{
	private $client;

	public function setClient(Interfaces\Client $client): self
	{
		$this->client = $client;
		return $this;
	}

	public function getClient(): Interfaces\Client
	{
		return $this->client;
	}
}
