<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Selects;

use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Notifications\Slack\Contracts\AccessoryContract;
use InvalidArgumentException;

/**
 * Abstract class representing a base structure for select elements.
 *
 * The class provides functionality for defining interaction identifiers,
 * placeholders, and focus behavior. It also enforces subclasses to
 * implement additional fields specific to their select type.
 */
abstract class SelectElement implements AccessoryContract
{
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
     * A text object that defines the select's text.
     *
     * Can only be of type: plain_text. Text may truncate with ~30 characters.
     *
     * Maximum length for the text in this field is 75 characters.
     */
    protected ?PlainTextOnlyTextObject $placeholder = null;

    /**
     * Indicates whether the element should automatically gain focus when the view loads.
     *
     * When set to `true`, this element will automatically receive focus in the UI.
     * Useful for prioritizing user interaction.
     */
    protected ?bool $focusOnLoad = null;

    /**
     * Set the action ID for the select.
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
     * Set the placeholder text.
     */
    public function placeholder(string $text): self
    {
        $this->placeholder = new PlainTextOnlyTextObject($text);

        return $this;
    }

    /**
     * Set whether the element should automatically gain focus when the view loads.
     */
    public function focusOnLoad(bool $focusOnLoad = true): self
    {
        $this->focusOnLoad = $focusOnLoad;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $allFields = array_merge($this->extensionFields(), [
            'action_id' => $this->actionId,
            'placeholder' => $this->placeholder?->toArray(),
            'focus_on_load' => $this->focusOnLoad,
        ]);

        return array_filter(
            $allFields,
            static fn ($value): bool => $value !== null,
        );
    }

    /**
     * Get additional fields specific to the child class as an associative array.
     *
     * This method should be implemented in subclasses to provide additional
     * fields required by the specific select element type.
     *
     * @return array The additional fields for the select element.
     */
    abstract protected function extensionFields(): array;
}
