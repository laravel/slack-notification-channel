<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockInput extends SlackMessageBlock
{
	/**
	 * Create a new Input Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('input', $block_id);
	}

	/**
	 * Set the label that appears above an input element.
	 *
	 * @param  string  $label
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function label($label, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($label, $emoji);

		$this->payload['label'] = $pt->toArray();

		return $this;
	}

	/**
	 * An plain-text input element, a select menu element, a multi-select menu element, or a datepicker.
	 *
	 * @param  string  $type
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function element(string $type, Closure $callback)
	{
		$element = Elements\SlackMessageElement::create($type);

		$callback($element);

		$this->payload['element'] = $element->toArray();

		return $this;
	}

	/**
	 * Set the optional hint that appears below an input element in a lighter grey.
	 *
	 * @param  string  $hint
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function hint($hint, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($hint, $emoji);

		$this->payload['hint'] = $pt->toArray();

		return $this;
	}

	/**
	 * Whether the input element may be empty when a user submits the modal.
	 *
	 * @param  bool|null $optional
	 * @return $this
	 */
	public function optional($optional)
	{
		if (is_null($optional)) {
			unset($this->payload['optional']);
		} else {
			$this->payload['optional'] = $optional;
		}

		return $this;
	}
}