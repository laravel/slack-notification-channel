<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementSelectStatic extends SlackMessageElement
{
	/**
	 * Create a new Static Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('static_select', $action_id);
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
	 * Set an array of option group objects. 
	 * If options is specified, this field should not be.
	 *
	 * @param  array|null  $option_groups
	 * @return $this
	 */
	public function setOptionGroups(array $option_groups)
	{
		if (is_null($option_groups)) {
			unset($this->payload['option_groups']);
		} else {
			$this->payload['option_groups'] = $option_groups;
		}

		return $this;
	}

	/**
	 * Add an option object.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addOptionGroup(Closure $callback)
	{
		$option = new Objects\SlackMessageObjectOptionGroup;

		$callback($option);

		$this->payload['options'][] = $option->toArray();

		return $this;
	}

	/**
	 * Set a single option object that exactly matches one or more of the options within options or option_groups.
	 * This option will be selected when the menu initially loads.
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