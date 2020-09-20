<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockSection extends SlackMessageBlock
{
	/**
	 * Create a new Section Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('section', $block_id);
	}

	/**
	 * Set the  text for the block.
	 * This field is not required if a valid array of fields objects is provided instead.
	 * 
	 * @param  string  $text
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function text(Closure $callback, $mrkdwn = false)
	{
		$text = $mrkdwn ? new Objects\SlackMessageObjectMarkdownText : new Objects\SlackMessageObjectPlainText;
		
		$callback($text);

		$this->payload['text'] = $text->toArray();

		return $this;
	}

	/**
	 * Set an array of text objects.
	 * Any text objects included with fields will be rendered in a
	 * compact format that allows for 2 columns of side-by-side text. 
	 *
	 * @param  array|null  $fields
	 * @return $this
	 */
	public function setFields(array $fields)
	{
		if (is_null($fields)) {
			unset($this->payload['fields']);
		} else {
			$this->payload['fields'] = $fields;
		}

		return $this;
	}

	/**
	 * Add an element object.
	 *
	 * @param  string  $type
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addField(Closure $callback, $mrkdwn = false)
	{
		$text = $mrkdwn ? new Objects\SlackMessageObjectMarkdownText : new Objects\SlackMessageObjectPlainText;
		
		$callback($text);

		$this->payload['fields'][] = $text->toArray();

		return $this;
	}

	/**
	 * One of the available element objects.
	 *
	 * @param  string  $type
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function accessory(string $type, Closure $callback)
	{
		$element = Elements\SlackMessageElement::create($type);

		$callback($element);

		$this->payload['accessory'] = $element->toArray();

		return $this;
	}
}