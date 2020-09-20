<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementDatePicker extends SlackMessageElement
{
	/**
	 * Create a new Date Picker Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('datepicker', $action_id);
	}

	/**
	 * Set the placeholder text shown on the datepicker.
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
	 * Set the initial date that is selected when the element is loaded.
	 * This should be in the format YYYY-MM-DD.
	 *
	 * @param  string|null $initial_date
	 * @return $this
	 */
	public function initialDate($initial_date)
	{
		if (is_null($initial_date)) {
			unset($this->payload['initial_date']);
		} else {
			$this->payload['initial_date'] = $initial_date;
		}

		return $this;
	}
}