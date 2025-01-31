<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Traits;

use Illuminate\Support\Str;

trait DefaultIdTrait
{
    /**
     * Resolves a default unique identifier based on the given text and optional prefix.
     *
     * If no text is provided, a unique ID is generated. The identifier is sanitized
     * and trimmed to ensure compliance with expected formatting.
     *
     * @param  string|null  $text  The base text to generate the ID from. Defaults to a unique ID.
     * @param  string|null  $prefix  An optional prefix to prepend to the ID.
     * @return string The resolved default identifier.
     */
    private function resolveDefaultId(?string $prefix = '', ?string $text = null): string
    {
        $text = $text ?? uniqid();

        return $prefix.Str::lower(Str::slug(substr($text, 0, 248)));
    }
}
