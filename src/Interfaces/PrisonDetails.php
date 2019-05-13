<?php

namespace BudTask\Interfaces;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

interface PrisonDetails
{
	/**
	 * Set the name of the prisoner
	 */
	public function setPrisoner(string $prisoner);

	/**
	 * Get the name of the prisoner
	 */
	public function getPrisoner(): string;

	/**
	 * Get the cell where the prisoner is hold
	 */
	public function getCell(): string;

	/**
	 * Get the block where the prisoner is hold
	 */
	public function getBlock(): string;
}
