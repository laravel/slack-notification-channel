<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementRadioButtons extends SlackMessageElement
{
	/**
	 * Create a new Radio Buttons Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('radio_buttons', $action_id);
	}

	/**
	 * Set an array of option objects.
	 *
	 * @param  array  $options
	 * @return $this
	 */
	public function setOptions(array $options)
	{
		if (is_null($options)) {
			unset($this->payload['options']);
		} else {
			$this->payload['options'] = $options;
		}

		return $this;
	}

	/**
	 * Add an option object.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addOption(Closure $callback)
	{
		$option = new Objects\SlackMessageObjectOption;

		$callback($option);

		$this->payload['options'][] = $option->toArray();

		return $this;
	}

	/**
	 * Set a single option object that exactly matches one of the options within options.
	 * This option will be selected when the radio button group initially loads.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function initialOption(Closure $callback)
	{
		$option = new Objects\SlackMessageObjectOption;

		$callback($option);

		$this->payload['initial_option'] = $option->toArray();

		return $this;
	}
}