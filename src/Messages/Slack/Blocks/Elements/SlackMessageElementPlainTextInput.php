<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementPlainTextInput extends SlackMessageElement
{
	/**
	 * Create a new Plain-text Input Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('plain_text_input', $action_id);
	}

	/**
	 * Set the placeholder text shown on the menu.
	 *
	 * @param  string  $placeholder
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function placeholder($placeholder, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($placeholder, $emoji);

		$this->payload['placeholder'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the initial value in the plain-text input when it is loaded.
	 *
	 * @param  string|null  $initial_value
	 * @return $this
	 */
	public function initialValue($initial_value)
	{
		if (is_null($initial_value)) {
			unset($this->payload['initial_value']);
		} else {
			$this->payload['initial_value'] = $initial_value;
		}

		return $this;
	}

	/**
	 * Set the initial value in the plain-text input when it is loaded.
	 *
	 * @param  bool|null  $multiline
	 * @return $this
	 */
	public function multiline($multiline)
	{
		if (is_null($multiline)) {
			unset($this->payload['multiline']);
		} else {
			$this->payload['multiline'] = $multiline;
		}

		return $this;
	}

	/**
	 * Specifies the minimum length of input that the user must provide.
	 * If the user provides less, they will receive an error.
	 * Maximum value is 3000.
	 *
	 * @param  int|null $min_length
	 * @return $this
	 */
	public function minLength($min_length)
	{
		if (is_null($min_length)) {
			unset($this->payload['min_length']);
		} else {
			$this->payload['min_length'] = $min_length;
		}

		return $this;
	}

	/**
	 * Specifies the maximum length of input that the user can provide.
	 * If the user provides more, they will receive an error.
	 *
	 * @param  int|null $max_length
	 * @return $this
	 */
	public function maxLength($max_length)
	{
		if (is_null($max_length)) {
			unset($this->payload['max_length']);
		} else {
			$this->payload['max_length'] = $max_length;
		}

		return $this;
	}
}