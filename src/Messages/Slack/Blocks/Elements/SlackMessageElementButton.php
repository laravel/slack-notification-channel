<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementButton extends SlackMessageElement
{
	/**
	 * Create a new Button Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('button', $action_id);
	}

	/**
	 * Set the text of the button.
	 *
	 * @param  string  $text
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function text($text, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($text, $emoji);

		$this->payload['text'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the URL to load in the user's browser when the button is clicked.
	 *
	 * @param  string|null $url
	 * @return $this
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

	/**
	 * Set the value to send along with the interaction payload.
	 *
	 * @param  string|null $value
	 * @return $this
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
	 * Set the color scheme applied to the button.
	 *
	 * @param  string|null  $style - 'danger', 'primary'
	 * @return void
	 */
	public function style($style)
	{
		if (is_null($style)) {
			unset($this->payload['style']);
		} else {
			$this->payload['style'] = $style;
		}

		return $this;
	}
}