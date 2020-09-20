<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockActions extends SlackMessageBlock
{
	use Elements\SlackMessageElementContainer;

	/**
	 * Create a new Actions Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('actions', $block_id);
	}
}