<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\TextObject;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Notifications\Slack\Contracts\ElementContract;
use InvalidArgumentException;
use LogicException;

class SectionBlock implements BlockContract
{
    /**
     * A string acting as a unique identifier for a block.
     *
     * If not specified, a block_id will be generated.
     *
     * You can use this block_id when you receive an interaction payload to identify the source of the action.
     */
    protected ?string $blockId = null;

    /**
     * The text for the block, in the form of a text object.
     *
     * Minimum length for the text in this field is 1 and maximum length is 3000 characters.
     *
     * This field is not required if a valid array of fields objects is provided instead.
     */
    protected ?TextObject $text = null;

    /**
     * Required if no text is provided. An array of text objects.
     *
     * Any text objects included with fields will be rendered in a compact format
     * that allows for 2 columns of side-by-side text. Maximum number of items
     * is 10 while maximum item content length is capped at 2000 characters.
     *
     * @var \Illuminate\Notifications\Slack\BlockKit\Composites\TextObject[]
     */
    protected array $fields = [];

    /**
     * One of the available element objects.
     */
    protected ?ElementContract $accessory = null;

    /**
     * Set the block identifier.
     */
    public function id(string $id): self
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * Set the text for the block.
     */
    public function text(string $text): TextObject
    {
        $this->text = $object = new TextObject($text, 3000);

        return $object;
    }

    /**
     * Add a field to the block.
     */
    public function field(string $text): TextObject
    {
        $this->fields[] = $field = new TextObject($text, 2000, 1);

        return $field;
    }

    /**
     * Set the accessory for the block.
     */
    public function accessory(ElementContract $element): self
    {
        $this->accessory = $element;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if (is_null($this->text) && empty($this->fields)) {
            throw new LogicException('A section requires at least one block, or the text to be set.');
        }

        if (count($this->fields) > 10) {
            throw new LogicException('There is a maximum of 10 fields in each section block.');
        }

        $optionalFields = array_filter([
            'text' => $this->text?->toArray(),
            'block_id' => $this->blockId,
            'accessory' => $this->accessory?->toArray(),
            'fields' => array_map(fn (Arrayable $element) => $element->toArray(), $this->fields),
        ]);

        return array_merge([
            'type' => 'section',
        ], $optionalFields);
    }
}
