<?php

namespace BudTask;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class ClientTest extends TestCase
{
	public function getImplementations(): array
	{
		$clients[] = new Client();
		return [$clients];
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_getToken(Interfaces\Client $obj)
	{
		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->setGuzzleClient($guzzle_client);

		$this->assertSame('foo', $obj->getToken());
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_getPrisonDetails(Interfaces\Client $obj)
	{
		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
			new Response(200, [], json_encode('foo')),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->setGuzzleClient($guzzle_client);

		$this->assertSame('foo', json_decode($obj->getPrisonDetails('leia')->getBody()->getContents()));
	}
}
