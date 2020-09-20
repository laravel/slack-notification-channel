<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementSelectUsers extends SlackMessageElement
{
	/**
	 * Create a new User Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('users_select', $action_id);
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
	 * Set a user ID of any valid users to be pre-selected when the menu loads.
	 *
	 * @param  string|null  $initial_user
	 * @return $this
	 */
	public function initialUser(string $initial_user)
	{
		if (is_null($initial_user)) {
			unset($this->payload['initial_user']);
		} else {
			$this->payload['initial_user'] = $initial_user;
		}

		return $this;
	}
}