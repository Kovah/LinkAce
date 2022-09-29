<?php

namespace App\Enums;

class ActivityLog
{
    public const SYSTEM_CRON_TOKEN_REGENERATED = 'system.cron_token_regenerated';

    public const USER_API_TOKEN_GENERATED = 'user.api_token_regenerated';
    public const USER_API_TOKEN_REVOKED = 'user.api_token_revoked';

    public const SYSTEM_API_TOKEN_GENERATED = 'system.api_token_regenerated';
    public const SYSTEM_API_TOKEN_REVOKED = 'system.api_token_revoked';
}
