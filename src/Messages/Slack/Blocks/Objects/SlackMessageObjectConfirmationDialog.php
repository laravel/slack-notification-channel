<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Objects;

use Closure;
use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;

class SlackMessageObjectConfirmationDialog extends SlackMessagePayload
{
	/**
	 * Set the title of the dialog.
	 *
	 * @param  string  $title
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function title($title, $emoji = null)
	{
		$pt = new SlackMessageObjectPlainText($title, $emoji);

		$this->payload['title'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the explanatory text that appears in the confirm dialog.
	 *
	 * @param  string  $text
	 * @param  bool  $mrkdwn
	 * @return $this
	 */
	public function text(Closure $callback, $mrkdwn = false)
	{
		$text = $mrkdwn ? new SlackMessageObjectMarkdownText : new SlackMessageObjectPlainText;
		
		$callback($text);

		$this->payload['text'] = $text->toArray();

		return $this;
	}

	/**
	 * Set the text of the button that confirms the action.
	 *
	 * @param  string  $confirm
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function confirm($confirm, $emoji = null)
	{
		$pt = new SlackMessageObjectPlainText($confirm, $emoji);

		$this->payload['confirm'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the text of the button that cancels the action.
	 *
	 * @param  string  $deny
	 * @param  bool|null  $emoji
	 * @return void
	 */
	public function deny($deny, $emoji = null)
	{
		$pt = new SlackMessageObjectPlainText($deny, $emoji);

		$this->payload['deny'] = $pt->toArray();

		return $this;
	}

	/**
	 * Set the color scheme applied to the confirm button.
	 *
	 * @param  string|null  $style - 'danger', 'primary'
	 * @return void
	 */
	public function style($style)
	{
		if (is_null($style)) {
			unset($this->payload['style']);
		} else {
			$this->payload['style'] = $style;
		}

		return $this;
	}
}