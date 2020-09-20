<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementCheckbox extends SlackMessageElement
{
	/**
	 * Create a new Checkbox Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('checkboxes', $action_id);
	}

	/**
	 * Set an array of option objects.
	 *
	 * @param  array|null  $options
	 * @return $this
	 */
	public function setOptions($options)
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
	 * Set an array of option objects that exactly matches one or more of the options within options.
	 * These options will be selected when the checkbox group initially loads.
	 *
	 * @param  array|null  $initial_options
	 * @return $this
	 */
	public function setInitialOptions($initial_options)
	{
		if (is_null($initial_options)) {
			unset($this->payload['initial_options']);
		} else {
			$this->payload['initial_options'] = $initial_options;
		}

		return $this;
	}

	/**
	 * Add an option object that exactly matches one or more of the options within options.
	 * These options will be selected when the checkbox group initially loads.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addInitialOption(Closure $callback)
	{
		$option = new Objects\SlackMessageObjectOption;

		$callback($option);

		$this->payload['initial_options'][] = $option->toArray();

		return $this;
	}
}