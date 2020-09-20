<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockFile extends SlackMessageBlock
{
	/**
	 * Create a new File Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('file', $block_id);
	}

	/**
	 * Set the external unique ID for this file.
	 *
	 * @param  string|null $external_id
	 * @return $this
	 */
	public function externalId($external_id)
	{
		if (is_null($external_id)) {
			unset($this->payload['external_id']);
		} else {
			$this->payload['external_id'] = $external_id;
		}

		return $this;
	}

	/**
	 * Set the source for this file.
	 * At the moment, source will always be "remote" for a remote file.
	 *
	 * @param  string|null $source
	 * @return $this
	 */
	public function source($source)
	{
		if (is_null($source)) {
			unset($this->payload['source']);
		} else {
			$this->payload['source'] = $source;
		}

		return $this;
	}
}