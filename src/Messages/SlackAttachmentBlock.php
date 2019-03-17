<?php

namespace Illuminate\Notifications\Messages;

class SlackAttachmentBlock
{
    /**
     * The type field of the attachment block.
     *
     * @var string
     */
    public $type;

    /**
     * The text field of the attachment block.
     *
     * @var array
     */
    public $text;

    /**
     * The block ID field of the attachment block.
     *
     * @var string
     */
    public $id;

    /**
     * The fields field of the attachment block.
     *
     * @var array
     */
    public $fields;

    /**
     * The accessory field of the attachment block.
     *
     * @var array
     */
    public $accessory;

    /**
     * The image url field of the attachment block.
     *
     * @var string
     */
    public $imageUrl;

    /**
     * The alt text field of the attachment block.
     *
     * @var string
     */
    public $altText;

    /**
     * The title field of the attachment block.
     *
     * @var array
     */
    public $title;

    /**
     * The elements field of the attachment block.
     *
     * @var array
     */
    public $elements;

    /**
     * Set the type of the block.
     *
     * @param  string  $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the text of the block.
     *
     * @param  array  $text
     * @return $this
     */
    public function text($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set the ID of the block.
     *
     * @param  string  $id
     * @return $this
     */
    public function id($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the fields of the block.
     *
     * @param  array  $fields
     * @return $this
     */
    public function fields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set the accessory of the block.
     *
     * @param  array  $accessory
     * @return $this
     */
    public function accessory($accessory)
    {
        $this->accessory = $accessory;

        return $this;
    }

    /**
     * Set the image url of the block.
     *
     * @param  string  $imageUrl
     * @return $this
     */
    public function imageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Set the alt text of the block.
     *
     * @param  string  $altText
     * @return $this
     */
    public function altText($altText)
    {
        $this->altText = $altText;

        return $this;
    }

    /**
     * Set the title of the block.
     *
     * @param  array  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the elements of the block.
     *
     * @param  array  $elements
     * @return $this
     */
    public function elements($elements)
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * Get the array representation of the attachment block.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'type' => $this->type,
            'text' => $this->text,
            'block_id' => $this->id,
            'fields' => $this->fields,
            'accessory' => $this->accessory,
            'image_url' => $this->imageUrl,
            'alt_text' => $this->altText,
            'title' => $this->title,
            'elements' => $this->elements,
        ]);
    }
}
