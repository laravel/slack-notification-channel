<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Composites\PlainTextOnlyTextObject;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use InvalidArgumentException;
use LogicException;

class ImageBlock implements BlockContract
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
     * The URL of the image to be displayed.
     *
     * Maximum length for this field is 3000 characters.
     */
    protected string $url;

    /**
     * A plain-text summary of the image.
     *
     * This should not contain any markup. Maximum length for this field is 2000 characters.
     */
    protected ?string $altText = null;

    /**
     * An optional title for the image in the form of a text object that can only be of type: plain_text.
     *
     * Maximum length for the text in this field is 2000 characters.
     */
    protected ?PlainTextOnlyTextObject $title = null;

    /**
     * Create a new image block instance.
     */
    public function __construct(string $url, ?string $altText = null)
    {
        if (strlen($url) > 3000) {
            throw new InvalidArgumentException('Maximum length for the url field is 3000 characters.');
        }

        $this->url = $url;
        $this->altText = $altText;
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
     * Set the alt text for the image.
     */
    public function alt(string $altText): self
    {
        if (strlen($altText) > 2000) {
            throw new InvalidArgumentException('Maximum length for the alt text field is 2000 characters.');
        }

        $this->altText = $altText;

        return $this;
    }

    /**
     * Set the title for the image.
     */
    public function title(string $title): PlainTextOnlyTextObject
    {
        $this->title = $object = new PlainTextOnlyTextObject($title, 2000);

        return $object;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if (is_null($this->altText)) {
            throw new LogicException('Alt text is required for an image block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
            'title' => $this->title?->toArray(),
        ]);

        return array_merge([
            'type' => 'image',
            'image_url' => $this->url,
            'alt_text' => $this->altText,
        ], $optionalFields);
    }
}
