<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementMultiSelectExternal extends SlackMessageElement
{
	/**
	 * Create a new Multi External Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('multi_external_select', $action_id);
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
	 * When the typeahead field is used, a request will be sent on every character change.
	 * If you prefer fewer requests or more fully ideated queries, use the min_query_length attribute
	 * to tell Slack the fewest number of typed characters required before dispatch.
	 * The default value is 3.
	 *
	 * @param  int|null $min_query_length
	 * @return $this
	 */
	public function minQueryLength($min_query_length)
	{
		if (is_null($min_query_length)) {
			unset($this->payload['min_query_length']);
		} else {
			$this->payload['min_query_length'] = $min_query_length;
		}

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