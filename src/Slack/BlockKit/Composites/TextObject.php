<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Support\Arr;

class TextObject extends PlainTextOnlyTextObject
{
    /**
     * The formatting to use for this text object.
     *
     * Can be one of "plain_text" or "mrkdwn".
     */
    protected string $type = 'plain_text';

    /**
     * Whether to skip any preprocessing / auto-conversion of URLs, conversation names, and certain mentions.
     *
     * Only applicable for mrkdwn text objects.
     */
    protected ?bool $verbatim = null;

    /**
     * Changes the formatting of this text object to mrkdwn.
     */
    public function markdown(): self
    {
        $this->type = 'mrkdwn';

        return $this;
    }

    /**
     * Indicate that URLs, conversation names and certain mentions should not be auto-linked.
     *
     * Only applicable for mrkdwn text objects.
     */
    public function verbatim(): self
    {
        $this->verbatim = true;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'verbatim' => $this->verbatim,
        ]);

        $built = array_merge(parent::toArray(), $optionalFields, [
            'type' => $this->type,
        ]);

        if ($this->type === 'mrkdwn') {
            return Arr::except($built, ['emoji']);
        }

        return Arr::except($built, ['verbatim']);
    }
}
