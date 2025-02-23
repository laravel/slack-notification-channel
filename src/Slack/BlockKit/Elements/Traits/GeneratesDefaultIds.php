<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Traits;

use Illuminate\Support\Str;

trait GeneratesDefaultIds
{
    /**
     * Resolves a default unique identifier based on the given text and optional prefix.
     */
    private function resolveDefaultId(?string $prefix = '', ?string $text = null): string
    {
        $text = $text ?? uniqid();

        return $prefix.Str::lower(Str::slug(substr($text, 0, 248)));
    }
}
