<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementMultiSelectStatic extends SlackMessageElement
{
	/**
	 * Create a new Multi Static Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('multi_static_select', $action_id);
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
	 * Set an array of option objects that exactly match one or more of the options within options or option_groups.
	 * These options will be selected when the menu initially loads.
	 *
	 * @param  array|null  $initial_options
	 * @return $this
	 */
	public function setInitialOptions(array $initial_options)
	{
		if (is_null($initial_options)) {
			unset($this->payload['initial_options']);
		} else {
			$this->payload['initial_options'] = $initial_options;
		}

		return $this;
	}

	/**
	 * Add an option objects that exactly match one or more of the options within options or option_groups.
	 * These options will be selected when the menu initially loads.
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

	/**
	 * Specifies the maximum number of items that can be selected in the menu.
	 *
	 * @param  int|null $max_selected_items
	 * @return $this
	 */
	public function maxSelectedItems($max_selected_items)
	{
		if (is_null($max_selected_items)) {
			unset($this->payload['max_selected_items']);
		} else {
			$this->payload['max_selected_items'] = $max_selected_items;
		}

		return $this;
	}
}