<?php

namespace Illuminate\Notifications\Slack;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ActionsBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\DividerBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\HeaderBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ImageBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Conditionable;
use LogicException;

class SlackMessage implements Arrayable
{
    use Conditionable;

    /**
     * The channel to send the message on.
     */
    protected ?string $channel = null;

    /**
     * The text content of the message.
     */
    protected ?string $text = null;

    /**
     * The message's blocks.
     *
     * @var \Illuminate\Notifications\Slack\Contracts\BlockContract[]
     */
    protected array $blocks = [];

    /**
     * The user emoji icon for the message.
     */
    protected ?string $icon = null;

    /**
     * The user image icon for the message.
     */
    protected ?string $image = null;

    /**
     * The JSON metadata for the message.
     */
    protected ?EventMetadata $metaData = null;

    /**
     * Indicates if you want the message to parse markdown or not.
     */
    protected ?bool $mrkdwn = null;

    /**
     * Indicates if you want a preview of links inlined in the message.
     */
    protected ?bool $unfurlLinks = null;

    /**
     * Indicates if you want a preview of links to media inlined in the message.
     */
    protected ?bool $unfurlMedia = null;

    /**
     * The username to send the message as.
     */
    protected ?string $username = null;

    /**
     * Set the Slack channel the message should be sent to.
     */
    public function to(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the fallback and notification text of the Slack message.
     */
    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Add a new Actions block to the message.
     */
    public function actionsBlock(Closure $callback): self
    {
        $this->blocks[] = $block = new ActionsBlock();

        $callback($block);

        return $this;
    }

    /**
     * Add a new Context block to the message.
     */
    public function contextBlock(Closure $callback): self
    {
        $this->blocks[] = $block = new ContextBlock();

        $callback($block);

        return $this;
    }

    /**
     * Add a new Divider block to the message.
     */
    public function dividerBlock(): self
    {
        $this->blocks[] = new DividerBlock();

        return $this;
    }

    /**
     * Add a new Header block to the message.
     */
    public function headerBlock(string $text, Closure $callback = null): self
    {
        $this->blocks[] = new HeaderBlock($text, $callback);

        return $this;
    }

    /**
     * Add a new Image block to the message.
     */
    public function imageBlock(string $url, Closure|string $altText = null, Closure $callback = null): self
    {
        if ($altText instanceof Closure) {
            $callback = $altText;
            $altText = null;
        }

        $this->blocks[] = $image = new ImageBlock($url, $altText);

        if ($callback) {
            $callback($image);
        }

        return $this;
    }

    /**
     * Add a new Section block to the message.
     */
    public function sectionBlock(Closure $callback): self
    {
        $this->blocks[] = $block = new SectionBlock();

        $callback($block);

        return $this;
    }

    /**
     * Set a custom image icon the message should use.
     */
    public function emoji(string $emoji): self
    {
        $this->image = null;
        $this->icon = $emoji;

        return $this;
    }

    /**
     * Set a custom image icon the message should use.
     */
    public function image(string $image): self
    {
        $this->icon = null;
        $this->image = $image;

        return $this;
    }

    /**
     * Set the metadata the message should include.
     */
    public function metadata(string $eventType, array $payload = []): self
    {
        $this->metaData = new EventMetadata($eventType, $payload);

        return $this;
    }

    /**
     * Disable Slack's markup parsing.
     */
    public function disableMarkdownParsing(): self
    {
        $this->mrkdwn = false;

        return $this;
    }

    /**
     * Unfurl links for rich display.
     */
    public function unfurlLinks(bool $unfurlLinks = true): self
    {
        $this->unfurlLinks = $unfurlLinks;

        return $this;
    }

    /**
     * Unfurl media for rich display.
     */
    public function unfurlMedia(bool $unfurlMedia = true): self
    {
        $this->unfurlMedia = $unfurlMedia;

        return $this;
    }

    /**
     * Set the user name for the Slack bot.
     */
    public function username(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if (empty($this->blocks) && $this->text === null) {
            throw new LogicException('Slack messages must contain at least a text message or block.');
        }

        if (count($this->blocks) > 50) {
            throw new LogicException('Slack messages can only contain up to 50 blocks.');
        }

        $optionalFields = array_filter([
            'text' => $this->text,
            'blocks' => ! empty($this->blocks) ? array_map(fn (BlockContract $block) => $block->toArray(), $this->blocks) : null,
            'icon_emoji' => $this->icon,
            'icon_url' => $this->image,
            'metadata' => $this->metaData?->toArray(),
            'mrkdwn' => $this->mrkdwn,
            'unfurl_links' => $this->unfurlLinks,
            'unfurl_media' => $this->unfurlMedia,
            'username' => $this->username,
        ], fn ($value) => $value !== null);

        return array_merge([
            'channel' => $this->channel,
        ], $optionalFields);
    }

    /**
     * Dump the payload as a URL to the Slack Block Kit Builder.
     *
     * @return void
     */
    public function dd(bool $raw = false)
    {
        if ($raw) {
            dd($this->toArray());
        }

        dd('https://app.slack.com/block-kit-builder#'.rawurlencode(json_encode(Arr::except($this->toArray(), ['username', 'text', 'channel']), true)));
    }
}
