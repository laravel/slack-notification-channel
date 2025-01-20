<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\TextObject;
use Illuminate\Notifications\Slack\BlockKit\Elements\ImageElement;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use InvalidArgumentException;
use LogicException;

class ContextBlock implements BlockContract
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
     * An array of image elements and text objects.
     *
     * Maximum number of items is 10.
     *
     * @var \Illuminate\Notifications\Slack\Contracts\ElementContract[]
     */
    protected array $elements = [];

    /**
     * Set the block identifier.
     */
    public function id(string $id): self
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * Add an image element to the block.
     */
    public function image(string $imageUrl, ?string $altText = null): ImageElement
    {
        return tap(new ImageElement($imageUrl, $altText), function (ImageElement $element) {
            $this->elements[] = $element;
        });
    }

    /**
     * Add a text element to the block.
     */
    public function text(string $text): TextObject
    {
        return tap(new TextObject($text), function (TextObject $element) {
            $this->elements[] = $element;
        });
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if (empty($this->elements)) {
            throw new LogicException('There must be at least one element in each context block.');
        }

        if (count($this->elements) > 10) {
            throw new LogicException('There is a maximum of 10 elements in each context block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'context',
            'elements' => array_map(fn (Arrayable $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
