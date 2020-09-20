<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectPlainText extends SlackMessagePayload
{
	/**
	 * Create a new Plain Text instance.
	 *
	 * @param  string  $text
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function __construct($text = '', $emoji = null)
	{
		$this->payload['type'] = 'plain_text';
		$this->payload['text'] = $text;

		if (!is_null($emoji)) {
			$this->payload['emoji'] = $emoji;
		}
	}

	/**
	 * Set the content of the PlainText.
	 *
	 * @param  string  $text
	 * @param  bool|null  $emoji
	 * @return $this
	 */
	public function text($text, $emoji = null)
	{
		$this->payload['text'] = $text;

		if (is_null($emoji)) {
			unset($this->payload['emoji']);
		} else {
			$this->payload['emoji'] = $emoji;
		}

		return $this;
	}
}