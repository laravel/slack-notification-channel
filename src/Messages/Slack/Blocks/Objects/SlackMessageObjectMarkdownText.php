<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectMarkdownText extends SlackMessagePayload
{
	/**
	 * Create a new Markdown Text instance.
	 *
	 * @param  string  $text
	 * @param  bool|null  $verbatim
	 * @return void
	 */
	public function __construct($text = '', $verbatim = null)
	{
		$this->payload['type'] = 'mrkdwn';
		$this->payload['text'] = $text;

		if (!is_null($verbatim)) {
			$this->payload['verbatim'] = $verbatim;
		}
	}

	/**
	 * Set the content of the PlainText.
	 *
	 * @param  string  $text
	 * @param  bool|null  $verbatim
	 * @return $this
	 */
	public function text($text, $verbatim = null)
	{
		$this->payload['text'] = $text;

		if (is_null($verbatim)) {
			unset($this->payload['verbatim']);
		} else {
			$this->payload['verbatim'] = $verbatim;
		}

		return $this;
	}
}