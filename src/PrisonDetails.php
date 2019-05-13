<?php

namespace BudTask;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class PrisonDetails
{
	use Traits\ClientTrait;

	private $prisoner;
	private $response;

	public function __construct(Interfaces\Client $client, string $prisoner)
	{
		$this->setClient($client);
		$this->setPrisoner($prisoner);
	}

	public function setPrisoner(string $prisoner): self
	{
		$this->prisoner = $prisoner;
		$this->response = null;
		return $this;
	}

	public function getPrisoner(): string
	{
		return $this->prisoner;
	}

	private function getResponse(): ResponseInterface
	{
		if ($this->response === null) {
			$this->response = $this->getClient()->getPrisonDetails($this->getPrisoner());
		}

		return $this->response;
	}

	private function getJsonArray(): array
	{
		$json = $this->getResponse()->getBody();

		$decoded = json_decode($json, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exceptions\JsonException();
		}

		return $decoded;
	}

	public function getCell(): string
	{
		$json_array = $this->getJsonArray();

		if (! array_key_exists('cell', $json_array)) {
			throw new Exceptions\InvalidResponseException('Missing "cell"');
		}

		$decoded = Tools::decodeBin($json_array['cell']);

		if (preg_match('/^Cell [0-9]+$/u', $decoded) !== 1) {
			throw new Exceptions\InvalidResponseException('Invalid "cell"');
		}

		return $decoded;
	}

	public function getBlock(): string
	{
		$json_array = $this->getJsonArray();

		if (! array_key_exists('block', $json_array)) {
			throw new Exceptions\InvalidResponseException('Missing "block"');
		}

		$decoded = Tools::decodeBin($json_array['block']);

		if (preg_match('/^Detention Block [A-Z]+-[0-9]+$/u', $decoded) !== 1) {
			throw new Exceptions\InvalidResponseException('Invalid "block"');
		}

		return $decoded;
	}
}
