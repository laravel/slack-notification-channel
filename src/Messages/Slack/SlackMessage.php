<?php

namespace Illuminate\Notifications\Messages\Slack;

use Closure;

class SlackMessage extends SlackMessagePayload
{
	use Blocks\SlackMessageBlockContainer;

	/**
	 * Additional request options for the Guzzle HTTP client.
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Set the Slack channel the message should be sent to.
	 *
	 * @param  string  $channel
	 * @return $this
	 */
	public function to($channel)
	{
		if (is_null($channel)) {
			unset($this->payload['channel']);
		} else {
			$this->payload['channel'] = $channel;
		}

		return $this;
	}

	/**
	 * Set the content of the Slack message.
	 * This is used as a fallback string to display in notifications. 
	 * This field is not enforced as required when using `blocks`.
	 * It can be formatted as plain text, or with markdown.
	 *
	 * @param  string  $text
	 * @param  bool|null  $mrkdwn
	 * @return $this
	 */
	public function text($text, $mrkdwn = null)
	{
		$this->payload['text'] = $text;

		if (is_null($mrkdwn)) {
			unset($this->payload['mrkdwn']);
		} else {
			$this->payload['mrkdwn'] = $mrkdwn;
		}

		return $this;
	}

	/**
	 * Set attachments for the message.
	 *
	 * @param  array  $attachments
	 * @return $this
	 */
	public function setAttachments(array $attachments)
	{
		$this->payload['attachments'] = $attachments;

		return $this;
	}

	/**
	 * Add an attachment for the message.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function addAttachment(Closure $callback)
	{
		$attachment = new SlackMessageAttachment;

		$callback($attachment);

		$this->payload['attachments'][] = $attachment->toArray();

		return $this;
	}

	/**
	 * Set a custom username and optional emoji icon for the Slack message.
	 *
	 * @param  string  $username
	 * @param  string|null  $icon
	 * @return $this
	 */
	public function from($username, $icon = null)
	{
		$this->payload['username'] = $username;

		if (is_null($icon)) {
			unset($this->payload['icon_emoji']);
			unset($this->payload['icon_url']);
		} else if (!empty($icon) && $icon[0] === ':' && $icon[-1] === ':') {
				$this->payload['icon_emoji'] = $icon;
		} else {
			$this->payload['icon_url'] = $icon;
		}

		return $this;
	}

	/**
	 * Make this message a reply.
	 *
	 * @param  string  $thread_ts
	 * @param  bool|null  $broadcast
	 * @return $this
	 */
	public function reply(string $thread_ts, $broadcast = null)
	{
		$this->payload['thread_ts'] = $thread_ts;

		if (is_null($broadcast)) {
			unset($this->payload['reply_broadcast']);
		} else {
			$this->payload['reply_broadcast'] = $broadcast;
		}

		return $this;
	}

	/**
	 * Change how messages are treated.
	 *
	 * @param  string|null  $parse - 'none', 'full'
	 * @return $this
	 */
	public function parse($parse)
	{
		if (is_null($parse)) {
			unset($this->payload['parse']);
		} else {
			$this->payload['parse'] = $parse;
		}

		return $this;
	}

	/**
	 * Disable/Enable Slack markup parsing.
	 *
	 * @param  bool|null  $mrkdwn
	 * @return $this
	 */
	public function markdown($mrkdwn)
	{
		if (is_null($mrkdwn)) {
			unset($this->payload['mrkdwn']);
		} else {
			$this->payload['mrkdwn'] = $mrkdwn;
		}

		return $this;
	}

	/**
	 * Find and link channel names and usernames.
	 *
	 * @param  bool|null  $linkNames
	 * @return $this
	 */
	public function linkNames($linkNames)
	{
		if (is_null($linkNames)) {
			unset($this->payload['link_names']);
		} else {
			$this->payload['link_names'] = $linkNames;
		}

		return $this;
	}

	/**
	 * Unfurl links to rich display.
	 *
	 * @param  bool|null  $unfurl
	 * @return $this
	 */
	public function unfurlLinks($unfurl)
	{
		if (is_null($unfurl)) {
			unset($this->payload['unfurl_links']);
		} else {
			$this->payload['unfurl_links'] = $unfurl;
		}

		return $this;
	}

	/**
	 * Unfurl media to rich display.
	 *
	 * @param  bool|null  $unfurl
	 * @return $this
	 */
	public function unfurlMedia($unfurl)
	{
		if (is_null($unfurl)) {
			unset($this->payload['unfurl_media']);
		} else {
			$this->payload['unfurl_media'] = $unfurl;
		}

		return $this;
	}

	/**
	 * Set additional request options for the Guzzle HTTP client.
	 *
	 * @param  array  $options
	 * @return $this
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;

		return $this;
	}

	/**
	 * Add additional request option for the Guzzle HTTP client.
	 *
	 * @param  string $key
	 * @param  mixed $value
	 * @return $this
	 */
	public function addOption(string $key, mixed $value)
	{
		$this->options[$key] = $value;

		return $this;
	}

	/**
	 * Get the additional request options for the Guzzle HTTP client.
	 *
	 * @return array
	 */
	public function options() : ?array
	{
		return $this->options;
	}
}