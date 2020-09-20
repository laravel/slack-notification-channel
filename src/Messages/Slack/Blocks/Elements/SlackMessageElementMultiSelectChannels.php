<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElementMultiSelectChannels extends SlackMessageElement
{
	/**
	 * Create a new Multi Channels Select Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('multi_channels_select', $action_id);
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
	 * Set an array of one or more IDs of any valid public channel to be pre-selected when the menu loads.
	 *
	 * @param  array|null  $initial_channels
	 * @return $this
	 */
	public function setInitialChannels(array $initial_channels)
	{
		if (is_null($initial_channels)) {
			unset($this->payload['initial_channels']);
		} else {
			$this->payload['initial_channels'] = $initial_channels;
		}

		return $this;
	}

	/**
	 * Add a user ID of any valid public channel to be pre-selected when the menu loads.
	 *
	 * @param  string  $channel
	 * @return $this
	 */
	public function addInitialChannel(string $channel)
	{
		$this->payload['initial_channels'][] = $channel;

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