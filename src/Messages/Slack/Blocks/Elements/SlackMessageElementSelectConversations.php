<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementSelectConversations extends SlackMessageElement
{
	/**
	 * Create a new Conversations Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('conversations_select', $action_id);
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
	 * Set the ID of any valid conversation to be pre-selected when the menu loads.
	 * If default_to_current_conversation is also supplied, initial_conversation will be ignored.
	 *
	 * @param  string|null  $initial_conversation
	 * @return $this
	 */
	public function initialConversation(string $initial_conversation)
	{
		if (is_null($initial_conversation)) {
			unset($this->payload['initial_conversation']);
		} else {
			$this->payload['initial_conversation'] = $initial_conversation;
		}

		return $this;
	}

	/**
	 * Pre-populates the select menu with the conversation that the user was viewing when they opened the modal, if available.
	 *
	 * @param  bool|null  $default_to_current_conversation
	 * @return $this
	 */
	public function defaultToCurrentConversation($default_to_current_conversation)
	{
		if (is_null($default_to_current_conversation)) {
			unset($this->payload['default_to_current_conversation']);
		} else {
			$this->payload['default_to_current_conversation'] = $default_to_current_conversation;
		}

		return $this;
	}

	/**
	 * Set the filter object that reduces the list of available conversations using the specified criteria.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function filter(Closure $callback)
	{
		$filter = new Objects\SlackMessageObjectFilter;

		$callback($filter);

		$this->payload['filter'] = $filter->toArray();

		return $this;
	}

	/**
	 * When set to true, the view_submission payload from the menu's parent view will contain a response_url.
	 * This response_url can be used for message responses.
	 * The target conversation for the message will be determined by the value of this select menu.
	 * 
	 * This field only works with menus in input blocks in modals.
	 *
	 * @param  bool|null $response_url_enabled
	 * @return $this
	 */
	public function responseUrlEnabled($response_url_enabled)
	{
		if (is_null($response_url_enabled)) {
			unset($this->payload['response_url_enabled']);
		} else {
			$this->payload['response_url_enabled'] = $response_url_enabled;
		}

		return $this;
	}
}