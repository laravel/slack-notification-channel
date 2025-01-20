<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use InvalidArgumentException;

class HeaderBlock implements BlockContract
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
     * The text for the block, in the form of a plain_text text object.
     *
     * Maximum length for the text in this field is 150 characters.
     */
    protected PlainTextOnlyTextObject $text;

    /**
     * Create a new header block instance.
     */
    public function __construct(string $text, ?Closure $callback = null)
    {
        $this->text = $object = new PlainTextOnlyTextObject($text, 150);

        if ($callback) {
            $callback($object);
        }
    }

    /**
     * Set the block identifier.
     */
    public function id(string $id): self
    {
        $this->blockId = $id;

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

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'header',
            'text' => $this->text->toArray(),
        ], $optionalFields);
    }
}
