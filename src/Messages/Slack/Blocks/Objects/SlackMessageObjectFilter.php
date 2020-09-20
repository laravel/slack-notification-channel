<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Closure;
use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectFilter extends SlackMessagePayload
{
	/**
	 * Exclude external shared channels from conversation lists.
	 *
	 * @param  bool|null  $exclude
	 * @return $this
	 */
	public function excludeExternalSharedChannels($exclude)
	{
		if (is_null($exclude)) {
			unset($this->payload['exclude_external_shared_channels']);
		} else {
			$this->payload['exclude_external_shared_channels'] = $exclude;
		}

		return $this;
	}

	/**
	 * Exclude bot users from conversation lists.
	 *
	 * @param  bool|null  $exclude
	 * @return $this
	 */
	public function excludeBotUsers($exclude)
	{
		if (is_null($exclude)) {
			unset($this->payload['exclude_bot_users']);
		} else {
			$this->payload['exclude_bot_users'] = $exclude;
		}

		return $this;
	}

	/**
	 * Set the types of conversations should be included in the list.
	 *
	 * @param  array  $types - 'im', 'mpim', 'private', 'public'
	 * @return $this
	 */
	public function setInclude(array $types)
	{
		if (is_null($types)) {
			unset($this->payload['include']);
		} else {
			$this->payload['include'] = $types;
		}

		return $this;
	}

	/**
	 * Add a type of conversations should be included in the list.
	 *
	 * @param  string  $type - 'im', 'mpim', 'private', 'public'
	 * @return $this
	 */
	public function addInclude(string $type)
	{
		$this->payload['include'][] = $type;

		return $this;
	}
}