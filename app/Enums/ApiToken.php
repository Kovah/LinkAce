<?php

namespace App\Enums;

class ApiToken
{
    public const ABILITY_USER_ACCESS = 'user_access';
    public const ABILITY_SYSTEM_ACCESS = 'system_access';
    public const ABILITY_SYSTEM_ACCESS_PRIVATE = 'system_access_private';

    public const ABILITY_LINKS_READ = 'links.read';
    public const ABILITY_LINKS_CREATE = 'links.create';
    public const ABILITY_LINKS_UPDATE = 'links.update';
    public const ABILITY_LINKS_DELETE = 'links.delete';

    public const ABILITY_LISTS_READ = 'lists.read';
    public const ABILITY_LISTS_CREATE = 'lists.create';
    public const ABILITY_LISTS_UPDATE = 'lists.update';
    public const ABILITY_LISTS_DELETE = 'lists.delete';

    public const ABILITY_TAGS_READ = 'tags.read';
    public const ABILITY_TAGS_CREATE = 'tags.create';
    public const ABILITY_TAGS_UPDATE = 'tags.update';
    public const ABILITY_TAGS_DELETE = 'tags.delete';

    public const ABILITY_NOTES_READ = 'notes.read';
    public const ABILITY_NOTES_CREATE = 'notes.create';
    public const ABILITY_NOTES_UPDATE = 'notes.update';
    public const ABILITY_NOTES_DELETE = 'notes.delete';

    public static array $systemTokenAbilities = [
        self::ABILITY_LINKS_READ,
        self::ABILITY_LINKS_CREATE,
        self::ABILITY_LINKS_UPDATE,
        self::ABILITY_LINKS_DELETE,
        self::ABILITY_LISTS_READ,
        self::ABILITY_LISTS_CREATE,
        self::ABILITY_LISTS_UPDATE,
        self::ABILITY_LISTS_DELETE,
        self::ABILITY_TAGS_READ,
        self::ABILITY_TAGS_CREATE,
        self::ABILITY_TAGS_UPDATE,
        self::ABILITY_TAGS_DELETE,
        self::ABILITY_NOTES_READ,
        self::ABILITY_NOTES_CREATE,
        self::ABILITY_NOTES_UPDATE,
        self::ABILITY_NOTES_DELETE,
    ];
}
