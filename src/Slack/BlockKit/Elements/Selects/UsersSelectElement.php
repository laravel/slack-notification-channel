<?php

namespace Illuminate\Notifications\Slack\BlockKit\Elements\Selects;

use Illuminate\Notifications\Slack\BlockKit\Elements\Traits\GeneratesDefaultIds;

class UsersSelectElement extends SelectElement
{
    use GeneratesDefaultIds;

    /**
     * The initially selected user, if applicable.
     */
    private ?string $initialUser = null;

    /**
     * Create a new users select element instance.
     */
    public function __construct()
    {
        $this->id($this->resolveDefaultId('users_select_'));
    }

    /**
     * Specify the ID of the user that should be selected by default.
     */
    public function initialUser(string $value): self
    {
        $this->initialUser = $value;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return array_filter(array_merge([
            'type' => 'users_select',
            'initial_user' => $this->initialUser,
        ], parent::toArray()), fn ($value): bool => $value !== null);
    }
}
