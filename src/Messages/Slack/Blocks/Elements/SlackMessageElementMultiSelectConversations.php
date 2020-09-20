<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementMultiSelectConversations extends SlackMessageElement
{
	/**
	 * Create a new Multi Conversations Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('multi_conversations_select', $action_id);
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
	 * Set an array of one or more IDs of any valid conversations to be pre-selected when the menu loads.
	 * If default_to_current_conversation is also supplied, initial_conversations will be ignored.
	 *
	 * @param  array|null  $initial_conversations
	 * @return $this
	 */
	public function setInitialConversations(array $initial_conversations)
	{
		if (is_null($initial_conversations)) {
			unset($this->payload['initial_conversations']);
		} else {
			$this->payload['initial_conversations'] = $initial_conversations;
		}

		return $this;
	}

	/**
	 * Add a user ID of any valid conversations to be pre-selected when the menu loads.
	 *
	 * @param  string  $conversation
	 * @return $this
	 */
	public function addInitialConversation(string $conversation)
	{
		$this->payload['initial_conversations'][] = $conversation;

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