<?php

namespace Illuminate\Notifications\Messages\Slack;


class SlackMessageAttachment extends SlackMessagePayload
{
	use Blocks\SlackMessageBlockContainer;

	public const ColorGood = 'good';
	public const ColorWarning = 'warning';
	public const ColorDanger = 'danger';

	/**
	 * Set the color of the attachment.
	 *
	 * @return $this
	 */
	public function good()
	{
		$this->payload['color'] = self::ColorGood;

		return $this;
	}

	/**
	 * Set the color of the attachment.
	 *
	 * @return $this
	 */
	public function warning()
	{
		$this->payload['color'] = self::ColorWarning;

		return $this;
	}

	/**
	 * Set the color of the attachment.
	 *
	 * @return $this
	 */
	public function danger()
	{
		$this->payload['color'] = self::ColorDanger;

		return $this;
	}

	/**
	 * Set the color of the attachment.
	 *
	 * @param  string|null  $color - 'good', 'warning', 'danger', or '#439FE0'
	 * @return $this
	 */
	public function color($color)
	{
		if (is_null($color)) {
			unset($this->payload['color']);
		} else {
			$this->payload['color'] = $color;
		}

		return $this;
	}

	/**
	 * Set the author of the attachment.
	 *
	 * @param  string  $name
	 * @param  string|null  $link
	 * @param  string|null  $icon
	 * @return $this
	 */
	public function author($name, $link = null, $icon = null)
	{
		$this->payload['author_name'] = $name;

		if (is_null($link)) {
			unset($this->payload['author_link']);
		} else {
			$this->payload['author_link'] = $link;
		}

		if (is_null($icon)) {
			unset($this->payload['author_icon']);
		} else {
			$this->payload['author_icon'] = $icon;
		}

		return $this;
	}

	/**
	 * A plain-text summary of the attachment.
	 *
	 * @param  string|null  $fallback
	 * @return $this
	 */
	public function fallback($fallback)
	{
		if (is_null($fallback)) {
			unset($this->payload['fallback']);
		} else {
			$this->payload['fallback'] = $fallback;
		}

		return $this;
	}

	/**
	 * Set the footer of the attachment.
	 *
	 * @param  string  $footer
	 * @param  string|null  $icon
	 * @return $this
	 */
	public function footer($footer, $icon = null)
	{
		$this->payload['footer'] = $footer;
		
		if (is_null($icon)) {
			unset($this->payload['footer_icon']);
		} else {
			$this->payload['footer_icon'] = $icon;
		}

		return $this;
	}
	
	/**
	 * Set the image URL.
	 *
	 * @param  string|null  $url
	 * @return $this
	 */
	public function image($url)
	{
		if (is_null($url)) {
			unset($this->payload['image_url']);
		} else {
			$this->payload['image_url'] = $url;
		}

		return $this;
	}

	/**
	 * Set the URL to the attachment thumbnail.
	 *
	 * @param  string|null  $url
	 * @return $this
	 */
	public function thumb($url)
	{
		if (is_null($url)) {
			unset($this->payload['thumb_url']);
		} else {
			$this->payload['thumb_url'] = $url;
		}

		return $this;
	}

	/**
	 * Set the title of the attachment.
	 *
	 * @param  string  $title
	 * @param  string|null  $url
	 * @return $this
	 */
	public function title($title, $url = null)
	{
		$this->payload['title'] = $title;

		if (is_null($url)) {
			unset($this->payload['title_link']);
		} else {
			$this->payload['title_link'] = $url;
		}

		return $this;
	}

	/**
	 * Set the pretext of the attachment.
	 *
	 * @param  string  $pretext
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function pretext($pretext, $mrkdwn = false)
	{
		$this->payload['pretext'] = $pretext;

		if ($mrkdwn) {
			$this->markdown('pretext');
		} else {
			if (array_key_exists('mrkdwn_in', $this->payload) &&
				is_array($this->payload['mrkdwn_in']) &&
				in_array('pretext', $this->payload['mrkdwn_in'])
			) {
				unset($this->payload['mrkdwn_in']['pretext']);
			}
		}

		return $this;
	}

	/**
	 * Set the content (text) of the attachment.
	 *
	 * @param  string  $text
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function text($text, $mrkdwn = false)
	{
		$this->payload['text'] = $text;

		if ($mrkdwn) {
			$this->markdown('text');
		} else {
			if (array_key_exists('mrkdwn_in', $this->payload) &&
				is_array($this->payload['mrkdwn_in']) &&
				in_array('text', $this->payload['mrkdwn_in'])
			) {
				unset($this->payload['mrkdwn_in']['text']);
			}
		}

		return $this;
	}

	/**
	 * Set the fields containing markdown.
	 *
	 * @param  array|string  $fields - 'text', 'pretext', 'fields'
	 * @return $this
	 */
	public function markdown($fields)
	{
		if (is_array($fields)) {
			$this->payload['mrkdwn_in'] = $fields;
		} else {
			if (!array_key_exists('mrkdwn_in', $this->payload) || !is_array($this->payload['mrkdwn_in'])) {
				$this->payload['mrkdwn_in'] = [];
			}

			if (!in_array($fields, $this->payload['mrkdwn_in'])) {
				$this->payload['mrkdwn_in'][] = $fields;
			}
		}

		return $this;
	}

	/**
	 * Set the timestamp a DateTimeInterface, DateInterval, or the number of seconds that should be added to the current time.
	 *
	 * @param  \DateTimeInterface|\DateInterval|int  $timestamp
	 * @return $this
	 */
	public function timestamp($timestamp)
	{
		if ($timestamp instanceof \DateInterval) {
			$now = new \DateTime('now');
			$timestamp = $now->add($timestamp);
		}

		if ($timestamp instanceof \DateTimeInterface) {
			$timestamp = $timestamp->getTimestamp();
		}

		$this->payload['ts'] = $timestamp;

		return $this;
	}
	
	/**
	 * Set the fields of the attachment.
	 *
	 * @param  array  $fields
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function setFields(array $fields, $mrkdwn = false)
	{
		$this->payload['fields'] = $fields;

		if ($mrkdwn) {
			$this->markdown('fields');
		} else {
			if (array_key_exists('mrkdwn_in', $this->payload) &&
				is_array($this->payload['mrkdwn_in']) &&
				in_array('fields', $this->payload['mrkdwn_in'])
			) {
				unset($this->payload['mrkdwn_in']['fields']);
			}
		}

		return $this;
	}

	/**
	 * Add a field to the attachment.
	 *
	 * @param  \Closure|string  $title
	 * @param  string  $value
	 * @param  bool|null  $short
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function addField($title, $value = null, $short = null, $mrkdwn = false)
	{
		if (is_callable($title)) {
			$callback = $title;
			$field = new SlackMessageAttachmentField;

			$callback($field);
		} else {
			$field = new SlackMessageAttachmentField($title, $value, $short);
			$field->markdown($mrkdwn);
		}

		$this->payload['fields'][] = $field->toArray();

		if ($field->isMarkdown()) {
			$this->markdown('fields');
		}

		return $this;
	}
}
