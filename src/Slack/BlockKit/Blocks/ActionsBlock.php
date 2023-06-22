<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Elements\ButtonElement;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use InvalidArgumentException;
use LogicException;

class ActionsBlock implements BlockContract
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
     * An array of interactive element objects - buttons, select menus, overflow menus, or date pickers.
     *
     * There is a maximum of 25 elements in each action block.
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
     * Add a button element to the block.
     */
    public function button(string $text): ButtonElement
    {
        return tap(new ButtonElement($text), function (ButtonElement $button) {
            $this->elements[] = $button;
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
            throw new LogicException('There must be at least one element in each actions block.');
        }

        if (count($this->elements) > 25) {
            throw new LogicException('There is a maximum of 25 elements in each actions block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'actions',
            'elements' => array_map(fn (Arrayable $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
