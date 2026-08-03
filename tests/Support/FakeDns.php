<?php

namespace Tests\Support;

/**
 * Controls the return values of the namespaced dns_get_record()/gethostbynamel()
 * overrides in tests/Support/DnsInterceptStub.php, used to deterministically
 * reproduce GHSA-x8w7-mhjm-xvj2 without depending on real DNS resolution.
 */
class FakeDns
{
    public static array|false $dnsGetRecordResult = false;
    public static array|false $gethostbynamelResult = false;

    public static function reset(): void
    {
        self::$dnsGetRecordResult = false;
        self::$gethostbynamelResult = false;
    }
}
