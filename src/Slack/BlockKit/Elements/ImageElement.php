<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements;

use Illuminate\Notifications\Slack\Contracts\ElementContract;
use LogicException;

class ImageElement implements ElementContract
{
    /**
     * The URL of the image to be displayed.
     */
    protected string $url;

    /**
     * A plain-text summary of the image. This should not contain any markup.
     */
    protected ?string $altText;

    /**
     * Create a new image element instance.
     */
    public function __construct(string $url, ?string $altText = null)
    {
        $this->url = $url;
        $this->altText = $altText;
    }

    /**
     * Set the alt text for the image.
     */
    public function alt(string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if (is_null($this->altText)) {
            throw new LogicException('Alt text is required for an image element.');
        }

        return [
            'type' => 'image',
            'image_url' => $this->url,
            'alt_text' => $this->altText,
        ];
    }
}
