<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockHeader extends SlackMessageBlock
{
	/**
	 * Create a new Header Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('header', $block_id);
	}

	/**
	 * Set the text for the block.
	 *
	 * @param  string  $text
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function text($text, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($text, $emoji);

		$this->payload['text'] = $pt->toArray();

		return $this;
	}
}