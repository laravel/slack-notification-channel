<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementSelectChannels extends SlackMessageElement
{
	/**
	 * Create a new Channels Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('channels_select', $action_id);
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
	 * Set the ID of any valid public channel to be pre-selected when the menu loads.
	 *
	 * @param  string|null  $initial_channel
	 * @return $this
	 */
	public function initialChannel(string $initial_channel)
	{
		if (is_null($initial_channel)) {
			unset($this->payload['initial_channel']);
		} else {
			$this->payload['initial_channel'] = $initial_channel;
		}

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