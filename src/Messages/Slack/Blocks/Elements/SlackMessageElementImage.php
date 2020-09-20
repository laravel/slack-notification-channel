<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

class SlackMessageElementImage extends SlackMessageElement
{
	/**
	 * Create a new Image Element instance.
	 *
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct($action_id = null)
	{
		parent::__construct('image', $action_id);
	}

	/**
	 * Set the URL of the image to be displayed.
	 *
	 * @param  string|null $image_url
	 * @return $this
	 */
	public function imageUrl($image_url)
	{
		if (is_null($image_url)) {
			unset($this->payload['image_url']);
		} else {
			$this->payload['image_url'] = $image_url;
		}

		return $this;
	}

	/**
	 * Set the plain-text summary of the image. This should not contain any markup.
	 *
	 * @param  string|null $alt_text
	 * @return $this
	 */
	public function altText($alt_text)
	{
		if (is_null($alt_text)) {
			unset($this->payload['alt_text']);
		} else {
			$this->payload['alt_text'] = $alt_text;
		}

		return $this;
	}
}