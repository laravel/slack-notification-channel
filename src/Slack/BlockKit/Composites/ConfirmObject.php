<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Notifications\Slack\Contracts\ObjectContract;

class ConfirmObject implements ObjectContract
{
    /**
     * A plain_text-only text object that defines the dialog's title.
     *
     * Maximum length for this field is 100 characters.
     */
    protected PlainTextOnlyTextObject $title;

    /**
     * A text object that defines the explanatory text that appears in the confirm dialog.
     *
     * Maximum length for the text in this field is 300 characters.
     */
    protected TextObject $text;

    /**
     * A plain_text-only text object to define the text of the button that confirms the action.
     *
     * Maximum length for the text in this field is 30 characters.
     */
    protected PlainTextOnlyTextObject $confirm;

    /**
     * A plain_text-only text object to define the text of the button that cancels the action.
     *
     * Maximum length for the text in this field is 30 characters.
     */
    protected PlainTextOnlyTextObject $deny;

    /**
     * Defines the color scheme applied to the confirm button.
     *
     * A value of "danger" will display the button with a red background on desktop, or red text on mobile.
     * A value of "primary" will display the button with a green background on desktop, or blue text on mobile.
     *
     * If this field is not provided, the default value is "primary".
     */
    protected ?string $style = null;

    /**
     * Create a new confirm object instance.
     */
    public function __construct(string $text = 'Please confirm this action.')
    {
        $this->title('Are you sure?');
        $this->text($text);
        $this->confirm('Yes');
        $this->deny('No');
    }

    /**
     * Set the title of the confirm object.
     */
    public function title(string $title): PlainTextOnlyTextObject
    {
        $this->title = $object = new PlainTextOnlyTextObject($title, 100);

        return $object;
    }

    /**
     * Set the text of the confirm object.
     */
    public function text(string $text): TextObject
    {
        $this->text = $object = new TextObject($text, 300);

        return $object;
    }

    /**
     * Set the confirm button label of the confirm object.
     */
    public function confirm(string $label): PlainTextOnlyTextObject
    {
        $this->confirm = $object = new PlainTextOnlyTextObject($label, 30);

        return $object;
    }

    /**
     * Set the deny button label of the confirm object.
     */
    public function deny(string $label): PlainTextOnlyTextObject
    {
        $this->deny = $object = new PlainTextOnlyTextObject($label, 30);

        return $object;
    }

    /**
     * Marks the confirm dialog as dangerous.
     */
    public function danger(): self
    {
        $this->style = 'danger';

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'style' => $this->style,
        ]);

        return array_merge([
            'title' => $this->title->toArray(),
            'text' => $this->text->toArray(),
            'confirm' => $this->confirm->toArray(),
            'deny' => $this->deny->toArray(),
        ], $optionalFields);
    }
}
