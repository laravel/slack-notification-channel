<?php

namespace Illuminate\Notifications\Messages\Slack;

class SlackMessageAttachmentField extends SlackMessagePayload
{

	protected $markdown = false;

	/**
	 * Create a new Slack attachement field instance.
	 *
	 * @param  string  $title
	 * @param  string  $value
	 * @param  bool|null  $short
	 * @return void
	 */
	public function __construct($title = null, $value = null, $short = null)
	{
		if (!is_null($title)) {
			$this->payload['title'] = $title;
		}

		if (!is_null($value)) {
			$this->payload['value'] = $value;
		}

		if (!is_null($short)) {
			$this->payload['short'] = $short;
		}
	}

	/**
	 * Set the title of the field.
	 *
	 * @param  string|null $title
	 * @return $this
	 */
	public function title($title)
	{
		if (is_null($title)) {
			unset($this->payload['title']);
		} else {
			$this->payload['title'] = $title;
		}

		return $this;
	}

	/**
	 * Set the content of the field.
	 *
	 * @param  string|null $value
	 * @return $this
	 */
	public function value($value)
	{
		if (is_null($value)) {
			unset($this->payload['value']);
		} else {
			$this->payload['value'] = $value;
		}

		return $this;
	}

	/**
	 * Indicates that the content should be displayed side-by-side with other fields.
	 *
	 * @param  bool|null  $short
	 * @return $this
	 */
	public function short($short)
	{
		if (is_null($short)) {
			unset($this->payload['short']);
		} else {
			$this->payload['short'] = $short;
		}

		return $this;
	}

	/**
	 * Indicates that the content should not be displayed side-by-side with other fields.
	 *
	 * @return $this
	 */
	public function long()
	{
		$this->payload['short'] = false;

		return $this;
	}

	/**
	 * Indicates that the content should be formatted by markdown syntax..
	 *
	 * @param  bool  $short
	 * @return $this
	 */
	public function markdown($mrkdwn)
	{
		$this->markdown = $mrkdwn;

		return $this;
	}

	/**
	 * Indicates that the content should be formatted by markdown syntax..
	 *
	 * @return bool
	 */
	public function isMarkdown() : bool
	{
		return $this->markdown;
	}
}