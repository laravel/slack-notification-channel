<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementOverflow extends SlackMessageElement
{
	/**
	 * Create a new Overflow Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('overflow', $action_id);
	}

	/**
	 * Set an array of option objects to display in the menu.
	 * Maximum number of options is 5, minimum is 2.
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
}