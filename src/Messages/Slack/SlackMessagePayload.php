<?php

namespace Illuminate\Notifications\Messages\Slack;

class SlackMessagePayload
{
	/**
	 * The payload of the message.
	 *
	 * @var array
	 */
	protected $payload = [];

	/**
	 * Set payload of the message.
	 *
	 * @param  array  $payload
	 * @return $this
	 */
	public function setPayload(array $payload)
	{
		$this->payload = $payload;

		return $this;
	}

	/**
	 * Add additional payload of the message.
	 *
	 * @param  string $key
	 * @param  mixed $value
	 * @return $this
	 */
	public function addPayload(string $key, mixed $value)
	{
		$this->payload[$key] = $value;

		return $this;
	}

	/**
	 * Get the payload of the message.
	 *
	 * @return array
	 */
	public function payload() : ?array
	{
		return $this->payload;
	}

	/**
	 * Get the array representation of the message.
	 *
	 * @return array
	 */
	public function toArray() : ?array
	{
		return $this->payload;
	}
}