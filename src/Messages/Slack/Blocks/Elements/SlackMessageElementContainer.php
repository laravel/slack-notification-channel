<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;

trait SlackMessageElementContainer
{
	/**
	 * Set an array of interactive element objects - buttons, select menus, overflow menus, or date pickers.
	 * There is a maximum of 5 elements in each action block.
	 *
	 * @param  array|null  $elements
	 * @return $this
	 */
	public function setElements(array $elements)
	{
		if (is_null($elements)) {
			unset($this->payload['elements']);
		} else {
			$this->payload['elements'] = $elements;
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
	public function addElement(string $type, Closure $callback)
	{
		$element = Elements\SlackMessageElement::create($type);

		$callback($element);

		$this->payload['elements'][] = $element->toArray();

		return $this;
	}
}