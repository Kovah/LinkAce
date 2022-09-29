<?php

namespace App\Rules;

use App\Enums\ApiToken;
use Illuminate\Contracts\Validation\Rule;

class ApiTokenAbilityRule implements Rule
{
    private static array $availableAbilities = [
        ApiToken::ABILITY_LINKS_READ,
        ApiToken::ABILITY_LINKS_CREATE,
        ApiToken::ABILITY_LINKS_UPDATE,
        ApiToken::ABILITY_LINKS_DELETE,
        ApiToken::ABILITY_LISTS_READ,
        ApiToken::ABILITY_LISTS_CREATE,
        ApiToken::ABILITY_LISTS_UPDATE,
        ApiToken::ABILITY_LISTS_DELETE,
        ApiToken::ABILITY_TAGS_READ,
        ApiToken::ABILITY_TAGS_CREATE,
        ApiToken::ABILITY_TAGS_UPDATE,
        ApiToken::ABILITY_TAGS_DELETE,
        ApiToken::ABILITY_NOTES_READ,
        ApiToken::ABILITY_NOTES_CREATE,
        ApiToken::ABILITY_NOTES_UPDATE,
        ApiToken::ABILITY_NOTES_DELETE,
    ];

    public function passes($attribute, $value): bool
    {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!in_array($item, self::$availableAbilities, true)) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return trans('validation.custom.api_token_ability.api_token_ability');
    }
}
