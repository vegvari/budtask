<?php

namespace BudTask;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

final class Client implements Interfaces\Client
{
	private $client;
	private $token;

	public function setGuzzleClient(ClientInterface $client): self
	{
		$this->client = $client;
		return $this;
	}

	public function getGuzzleClient(): ClientInterface
	{
		if ($this->client === null) {
			$this->client = new GuzzleClient([
				'base_uri' => getenv('API_ENDPOINT'),
				'headers' => [
					'Accept' => 'application/json',
				],
			]);
		}

		return $this->client;
	}

	public function getToken(): string
	{
		if ($this->token === null) {
			$response = $this->getGuzzleClient()->request('POST', 'token', [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
				'form_params' => [
					'grant_type' => 'client_credentials',
					'client_id' => getenv('API_CLIENT_ID'),
					'client_secret' => getenv('API_CLIENT_SECRET'),
				],
			]);

			$this->token = $response->getHeader('access_token')[0];
		}

		return $this->token;
	}

	public function attackReactor(int $exhaust, int $torpedoes = 2): ResponseInterface
	{
		$endpoint = sprintf('%s/%d', self::ENDPOINT_REACTOR, $exhaust);

		$response = $this->getGuzzleClient()->request('DELETE', $endpoint, [
			'headers' => [
				'Authorization' => sprintf('Bearer %s', $this->getToken()),
				'Content-Type' => 'application/json',
				'X-Torpedoes' => $torpedoes,
			],
		]);

		return $response;
	}

	public function getPrisonDetails(string $prisoner): ResponseInterface
	{
		$endpoint = sprintf('%s/%s', self::ENDPOINT_PRISON, $prisoner);

		$response = $this->getGuzzleClient()->request('GET', $endpoint, [
			'headers' => [
				'Authorization' => sprintf('Bearer %s', $this->getToken()),
				'Content-Type' => 'application/json',
			],
		]);

		return $response;
	}
}
