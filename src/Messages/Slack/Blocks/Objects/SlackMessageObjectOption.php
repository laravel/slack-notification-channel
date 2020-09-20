<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Closure;
use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectOption extends SlackMessagePayload
{
	/**
	 * Set the text shown in the option on the menu.
	 * 
	 * Overflow, select, and multi-select menus can only use plain text.
	 * Radio buttons and checkboxes can use markdown text.
	 *
	 * @param  string  $text
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function text(Closure $callback, $mrkdwn = false)
	{
		$text = $mrkdwn ? new SlackMessageObjectMarkdownText : new SlackMessageObjectPlainText;
		
		$callback($text);

		$this->payload['text'] = $attachment->toArray();

		return $this;
	}

	/**
	 * Set the string value that will be passed to your app when this option is chosen.
	 *
	 * @param  string|null  $value
	 * @return void
	 */
	public function value($value)
	{
		if (is_null($value)) {
			unset($this->payload['value']);
		} else {
			$this->payload['value'] = $value;
		}

		return $this;
	}

	/**
	 * Set a line of descriptive text shown below the text field beside the radio button.
	 *
	 * @param  string  $description
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function description($description, $emoji = null)
	{
		$pt = new SlackMessageObjectPlainText($description, $emoji);

		$this->payload['description'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the URL to load in the user's browser when the option is clicked.
	 *
	 * @param  string|null  $url
	 * @return void
	 */
	public function url($url)
	{
		if (is_null($url)) {
			unset($this->payload['url']);
		} else {
			$this->payload['url'] = $url;
		}

		return $this;
	}
}