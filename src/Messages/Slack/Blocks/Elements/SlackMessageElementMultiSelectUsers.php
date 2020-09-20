<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementMultiSelectUsers extends SlackMessageElement
{
	/**
	 * Create a new Multi Users Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('multi_users_select', $action_id);
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
	 * Set an array of user IDs of any valid users to be pre-selected when the menu loads.
	 *
	 * @param  array|null  $initial_users
	 * @return $this
	 */
	public function setInitialUsers($initial_users)
	{
		if (is_null($initial_users)) {
			unset($this->payload['initial_users']);
		} else {
			$this->payload['initial_users'] = $initial_users;
		}

		return $this;
	}

	/**
	 * Add a user ID of any valid users to be pre-selected when the menu loads.
	 *
	 * @param  string  $user
	 * @return $this
	 */
	public function addInitialUser(string $user)
	{
		$this->payload['initial_users'][] = $user;

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