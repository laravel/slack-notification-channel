<?php

namespace Illuminate\Notifications\Messages\Slack\Blocks\Elements;

use Closure;
use Illuminate\Notifications\Messages\Slack\SlackMessagePayload;
use Illuminate\Notifications\Messages\Slack\Blocks\Objects;

class SlackMessageElement extends SlackMessagePayload
{
	protected static $elements = [
		'button' => SlackMessageElementButton::class,
		'checkboxes' => SlackMessageElementCheckbox::class,
		'datepicker' => SlackMessageElementDatePicker::class,
		'image' => SlackMessageElementImage::class,
		'multi_channels_select' => SlackMessageElementMultiSelectChannels::class,
		'multi_conversations_select' => SlackMessageElementMultiSelectConversations::class,
		'multi_external_select' => SlackMessageElementMultiSelectExternal::class,
		'multi_static_select' => SlackMessageElementMultiSelectStatic::class,
		'multi_users_select' => SlackMessageElementMultiSelectUsers::class,
		'overflow' => SlackMessageElementOverflow::class,
		'plain_text_input' => SlackMessageElementPlainTextInput::class,
		'radio_buttons' => SlackMessageElementRadioButtons::class,
		'channels_select' => SlackMessageElementSelectChannels::class,
		'conversations_select' => SlackMessageElementSelectConversations::class,
		'external_select' => SlackMessageElementSelectExternal::class,
		'static_select' => SlackMessageElementSelectStatic::class,
		'users_select' => SlackMessageElementSelectUsers::class,
	];

	/**
	 * Create a new Slack Message Element instance.
	 *
	 * @param  string  $type
	 * @param  string|null  $action_id
	 * @return void
	 */
	public function __construct(string $type, $action_id = null)
	{
		$this->payload['type'] = $type;

		if (!is_null($action_id)) {
			$this->payload['action_id'] = $id;
		}
	}

	/**
	 * Set the type of the element.
	 *
	 * @param  string|null $type
	 * @return $this
	 */
	public function type($type)
	{
		if (is_null($type)) {
			unset($this->payload['type']);
		} else {
			$this->payload['type'] = $type;
		}

		return $this;
	}

	/**
	 * Set the id of the element.
	 *
	 * @param  string|null $action_id
	 * @return $this
	 */
	public function actionId($action_id)
	{
		if (is_null($action_id)) {
			unset($this->payload['action_id']);
		} else {
			$this->payload['action_id'] = $action_id;
		}

		return $this;
	}

	/**
	 * Set an optional confirmation dialog after the button is clicked.
	 *
	 * @param  \Closure  $callback
	 * @return $this
	 */
	public function confirm(Closure $callback)
	{
		$confirm = new Objects\SlackMessageObjectConfirmationDialog;

		$callback($confirm);

		$this->payload['confirm'] = $confirm->toArray();

		return $this;
	}

	/**
	 * Create a new Element instance.
	 *
	 * @param  string  $type
	 * @param  string|null  $action_id
	 * @return mixed a new Element instance
	 */
	public static function create(string $type, $action_id = null)
	{
		if (array_key_exists($type, static::$elements)) {
			return new static::$elements[$type]($action_id);
		}

		return null;
	}
}