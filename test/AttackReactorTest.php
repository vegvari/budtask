<?php

namespace BudTask;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class AttackReactorTest extends TestCase
{
	public function getImplementations(): array
	{
		$clients[] = new AttackReactor(new Client(), 'leia');
		return [$clients];
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_getPrisonDetails($obj)
	{
		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
			new Response(200, [], json_encode([
				'result' => 'Boom! 2 torpedoes exploded in exhaust 1',
			])),
			new Response(200, [], json_encode([
				'result' => 'Boom! 5 torpedoes exploded in exhaust 2',
			])),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->getClient()->setGuzzleClient($guzzle_client);

		$response = $obj->launchTorpedo(1, 2);
		$result = json_decode($response->getBody(), true)['result'];
		$this->assertSame('Boom! 2 torpedoes exploded in exhaust 1', $result);

		$response = $obj->launchTorpedo(2, 5);
		$result = json_decode($response->getBody(), true)['result'];
		$this->assertSame('Boom! 5 torpedoes exploded in exhaust 2', $result);
	}
}
