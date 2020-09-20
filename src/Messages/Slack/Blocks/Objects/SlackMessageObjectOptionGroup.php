<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Closure;
use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectOptionGroup extends SlackMessagePayload
{
	/**
	 * Set the label shown above this group of options.
	 *
	 * @param  string  $label
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function label($label, $emoji = null)
	{
		$pt = new SlackMessageObjectPlainText($label, $emoji);

		$this->payload['label'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set an array of option objects that belong to this specific group.
	 *
	 * @param  array  $options
	 * @return $this
	 */
	public function setOptions(array $options)
	{
		$this->payload['options'] = $options;

		return $this;
	}

	/**
	 * Add an option object to this specific group.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addOption(Closure $callback)
	{
		$option = new SlackMessageObjectOption;

		$callback($option);

		$this->payload['options'][] = $option->toArray();

		return $this;
	}
}