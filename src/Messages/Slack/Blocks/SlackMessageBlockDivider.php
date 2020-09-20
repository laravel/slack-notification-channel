<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockDivider extends SlackMessageBlock
{
	/**
	 * Create a new Divider Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('divider', $block_id);
	}
}