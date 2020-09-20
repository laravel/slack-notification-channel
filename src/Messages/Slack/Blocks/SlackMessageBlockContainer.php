<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

use Closure;

trait SlackMessageBlockContainer
{
	/**
	 * Set blocks for the message.
	 *
	 * @param  array  $blocks
	 * @return $this
	 */
	public function setBlocks(array $blocks)
	{
		$this->payload['blocks'] = $blocks;

		return $this;
	}

	/**
	 * Add a Block for the message.
	 *
	 * @param  string  $type
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlock(string $type, Closure $callback)
	{
		$block = Blocks\SlackMessageBlock::create($type);

		$callback($block);

		$this->payload['blocks'][] = $block->toArray();

		return $this;
	}

	/**
	 * Add an Actions Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockActions(Closure $callback)
	{
		return $this->addBlock('actions', $callback);
	}

	/**
	 * Add a Context Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockContext(Closure $callback)
	{
		return $this->addBlock('context', $callback);
	}

	/**
	 * Add a Divider Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockDivider(Closure $callback)
	{
		return $this->addBlock('divider', $callback);
	}

	/**
	 * Add a File Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockFile(Closure $callback)
	{
		return $this->addBlock('file', $callback);
	}

	/**
	 * Add a Header Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockHeader(Closure $callback)
	{
		return $this->addBlock('header', $callback);
	}

	/**
	 * Add an Image Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockImage(Closure $callback)
	{
		return $this->addBlock('image', $callback);
	}

	/**
	 * Add an Input Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockInput(Closure $callback)
	{
		return $this->addBlock('input', $callback);
	}

	/**
	 * Add a Section Block for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addBlockSection(Closure $callback)
	{
		return $this->addBlock('section', $callback);
	}
}