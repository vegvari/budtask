<?php

namespace BudTask;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class AttackReactor
{
	use Traits\ClientTrait;

	public function __construct(Interfaces\Client $client = null)
	{
		$this->setClient($client);
	}

	public function launchTorpedo(int $exhaust, int $torpedoes): ResponseInterface
	{
		return $this->getClient()->attackReactor($exhaust, $torpedoes);
	}
}
