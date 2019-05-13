<?php

namespace BudTask;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class PrisonDetailsTest extends TestCase
{
	public function getImplementations(): array
	{
		$clients[] = new PrisonDetails(new Client(), 'leia');
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
				'cell' => '01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 0110111',
				'block' => '01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011',
			])),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->getClient()->setGuzzleClient($guzzle_client);

		$this->assertSame('Cell 2187', $obj->getCell());
		$this->assertSame('Detention Block AA-23', $obj->getBlock());
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_fail_getPrisonDetails_missing_cell($obj)
	{
		$this->expectException(Exceptions\InvalidResponseException::class);
		$this->expectExceptionMessage('Missing "cell"');

		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
			new Response(200, [], json_encode([
				'block' => '01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011',
			])),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->getClient()->setGuzzleClient($guzzle_client);

		$obj->getCell();
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_fail_getPrisonDetails_invalid_cell($obj)
	{
		$this->expectException(Exceptions\InvalidResponseException::class);
		$this->expectExceptionMessage('Invalid "cell"');

		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
			new Response(200, [], json_encode([
				'cell' => '',
				'block' => '',
			])),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->getClient()->setGuzzleClient($guzzle_client);

		$obj->getCell();
	}

	/**
	 * @dataProvider getImplementations
	 */
	public function test_fail_getPrisonDetails_missing_block($obj)
	{
		$this->expectException(Exceptions\InvalidResponseException::class);
		$this->expectExceptionMessage('Missing "block"');

		$mock = new MockHandler([
			new Response(200, ['access_token' => 'foo']),
			new Response(200, [], json_encode([
				'cell' => '',
			])),
		]);

		$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
		$obj->getClient()->setGuzzleClient($guzzle_client);

		$obj->getBlock();
	}

	// /**
	//  * @dataProvider getImplementations
	//  */
	// public function test_fail_getPrisonDetails_invalid_block($obj)
	// {
	// 	$this->expectException(Exceptions\InvalidResponseException::class);
	// 	$this->expectExceptionMessage('Missing "block"');

	// 	$mock = new MockHandler([
	// 		new Response(200, ['access_token' => 'foo']),
	// 		new Response(200, [], json_encode([
	// 			'block' => '',
	// 		])),
	// 	]);

	// 	$guzzle_client = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
	// 	$obj->getClient()->setGuzzleClient($guzzle_client);

	// 	$obj->getBlock();
	// }
}
