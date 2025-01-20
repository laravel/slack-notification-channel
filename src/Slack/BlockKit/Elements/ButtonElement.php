<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements;

use Closure;
use Illuminate\Notifications\Slack\BlockKit\Composites\ConfirmObject;
use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Notifications\Slack\Contracts\ElementContract;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ButtonElement implements ElementContract
{
    /**
     * A text object that defines the button's text.
     *
     * Can only be of type: plain_text. Text may truncate with ~30 characters.
     *
     * Maximum length for the text in this field is 75 characters.
     */
    protected PlainTextOnlyTextObject $text;

    /**
     * An identifier for this action.
     *
     * You can use this when you receive an interaction payload to identify the source of the action.
     *
     * Should be unique among all other action_ids in the containing block.
     *
     * Maximum length for this field is 255 characters.
     */
    protected string $actionId;

    /**
     * A URL to load in the user's browser when the button is clicked.
     *
     * Maximum length for this field is 3000 characters. If you're using a URL, you will still
     * receive an interaction payload and will need to send an acknowledgement response.
     *
     * @link https://api.slack.com/interactivity/handling#payloads
     * @link https://api.slack.com/interactivity/handling#acknowledgment_response
     */
    protected ?string $url = null;

    /**
     * The value to send along with the interaction payload.
     *
     * Maximum length for this field is 2000 characters.
     */
    protected ?string $value = null;

    /**
     * Decorates buttons with alternative visual color schemes. Use this option with restraint.
     *
     * - primary gives buttons a green outline and text, ideal for affirmation or confirmation actions.
     *   primary should only be used for one button within a set.
     *
     * - danger gives buttons a red outline and text, and should be used when the action is destructive.
     *   Use danger even more sparingly than primary.
     *
     * - If you don't include this field, the default button style will be used.
     */
    protected ?string $style = null;

    /**
     * A confirm object that defines an optional confirmation dialog after the button is clicked.
     */
    protected ?ConfirmObject $confirm = null;

    /**
     * A label for longer descriptive text about a button element.
     *
     * This label will be read out by screen readers instead of the button text object.
     *
     * Maximum length for this field is 75 characters.
     */
    protected ?string $accessibilityLabel = null;

    /**
     * Create a new button element instance.
     */
    public function __construct(string $text, ?Closure $callback = null)
    {
        $this->text = new PlainTextOnlyTextObject($text, 75);

        $this->id('button_'.Str::lower(Str::slug(substr($text, 0, 248))));

        if ($callback) {
            $callback($this->text);
        }
    }

    /**
     * Set the URL for the button.
     */
    public function url(string $url): self
    {
        if (strlen($url) > 3000) {
            throw new InvalidArgumentException('Maximum length for the url field is 3000 characters.');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Set the action ID for the button.
     */
    public function id(string $id): self
    {
        if (strlen($id) > 255) {
            throw new InvalidArgumentException('Maximum length for the action_id field is 255 characters.');
        }

        $this->actionId = $id;

        return $this;
    }

    /**
     * Set the value for the button.
     */
    public function value(string $value): self
    {
        if (strlen($value) > 2000) {
            throw new InvalidArgumentException('Maximum length for the value field is 2000 characters.');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Set the style for the button to primary.
     */
    public function primary(): self
    {
        $this->style = 'primary';

        return $this;
    }

    /**
     * Set the style for the button to danger.
     */
    public function danger(): self
    {
        $this->style = 'danger';

        return $this;
    }

    /**
     * Set the confirm object for the button.
     */
    public function confirm(string $text, ?Closure $callback = null): ConfirmObject
    {
        $this->confirm = $confirm = new ConfirmObject($text);

        if ($callback) {
            $callback($confirm);
        }

        return $confirm;
    }

    /**
     * Set the accessibility label for the button.
     */
    public function accessibilityLabel(string $label): self
    {
        if (strlen($label) > 75) {
            throw new InvalidArgumentException('Maximum length for the accessibility label is 75 characters.');
        }

        $this->accessibilityLabel = $label;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'url' => $this->url,
            'value' => $this->value,
            'style' => $this->style,
            'confirm' => $this->confirm?->toArray(),
            'accessibility_label' => $this->accessibilityLabel,
        ]);

        return array_merge([
            'type' => 'button',
            'text' => $this->text->toArray(),
            'action_id' => $this->actionId,
        ], $optionalFields);
    }
}
