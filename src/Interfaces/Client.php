<?php

namespace BudTask\Interfaces;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

interface Client
{
	const ENDPOINT_TOKEN = 'token';
	const ENDPOINT_REACTOR = 'reactor/exhaust';
	const ENDPOINT_PRISON = 'prison';

	/**
	 * Set the Guzzle client
	 */
	public function setGuzzleClient(ClientInterface $client);

	/**
	 * Get the Guzzle client, return default if not set
	 */
	public function getGuzzleClient(): ClientInterface;

	/**
	 * Get the bearer token
	 */
	public function getToken(): string;

	/**
	 * Send request to the reactor endpoint
	 */
	public function attackReactor(int $exhaust, int $torpedoes = 2): ResponseInterface;

	/**
	 * Send request to prison endpoint
	 */
	public function getPrisonDetails(string $prisoner): ResponseInterface;
}
