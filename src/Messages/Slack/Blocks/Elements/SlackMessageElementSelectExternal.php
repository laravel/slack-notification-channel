<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementSelectExternal extends SlackMessageElement
{
	/**
	 * Create a new External Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('external_select', $action_id);
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
	 * Add an option objects that exactly match one or more of the options within options or option_groups.
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