<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Selects;

use Illuminate\Notifications\Slack\BlockKit\Elements\Traits\DefaultIdTrait;
use InvalidArgumentException;

class StaticSelectElement extends SelectElement
{
    use DefaultIdTrait;

    /**
     * @var array<string, SelectOption> An array mapping values keys to SelectOption objects
     */
    private array $options = [];

    /**
     * @var SelectOption|null The initially selected option, or null if none is set
     */
    private ?SelectOption $initialOption = null;

    public function __construct()
    {
        $this->id($this->resolveDefaultId('static_select_'));
    }

    /**
     * Adds an option to the static select element.
     */
    public function addOption(string $text, string $value): self
    {
        $this->options[$value] = new SelectOption($text, $value);

        return $this;
    }

    /**
     * Sets the default selected option for the static select element.
     */
    public function initialOption(string $value): self
    {
        $option = $this->options[$value] ?? null;
        if ($option === null) {
            throw new InvalidArgumentException("Unknown option value: $value.");
        }

        $this->initialOption = $option;

        return $this;
    }

    protected function extensionFields(): array
    {
        $options = array_values($this->options);
        $options = array_map(fn (SelectOption $option) => $option->toArray(), $options);

        return [
            'type' => 'static_select',
            'options' => $options,
            'initial_option' => $this->initialOption?->toArray(),
        ];
    }
}
