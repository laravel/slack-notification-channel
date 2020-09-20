<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockContext extends SlackMessageBlock
{
	use Elements\SlackMessageElementContainer;

	/**
	 * Create a new Context Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('context', $block_id);
	}
}