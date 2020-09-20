<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks;

class SlackMessageBlockImage extends SlackMessageBlock
{
	/**
	 * Create a new Image Block instance.
	 *
	 * @param  string|null  $block_id
	 * @return void
	 */
	public function __construct($block_id = null)
	{
		parent::__construct('image', $block_id);
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
	 * Set the plain-text summary of the image.
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

	/**
	 * Set the optional title for the image.
	 *
	 * @param  string  $title
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function title($title, $emoji = null)
	{
		$pt = new Objects\SlackMessageObjectPlainText($title, $emoji);

		$this->payload['title'] = $pt->toArray();

		return $this;
	}
}