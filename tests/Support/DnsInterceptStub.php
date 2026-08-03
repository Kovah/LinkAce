<?php

/**
 * Namespaced overrides of dns_get_record()/gethostbynamel() for the two
 * call sites implicated in GHSA-x8w7-mhjm-xvj2: the vendored
 * Kovah\HtmlMeta\HtmlMeta::resolveHostIps() and this repo's own
 * App\Console\Commands\CheckLinksCommand::resolveHostIps(). Both call the
 * DNS functions unqualified, so PHP resolves them against the current
 * namespace first, letting these definitions shadow the real functions
 * for tests without touching real DNS or the vendor package.
 */

namespace Kovah\HtmlMeta {
    function dns_get_record(string $host, int $type): array|false
    {
        return \Tests\Support\FakeDns::$dnsGetRecordResult;
    }

    function gethostbynamel(string $host): array|false
    {
        return \Tests\Support\FakeDns::$gethostbynamelResult;
    }
}

namespace App\Console\Commands {
    function dns_get_record(string $host, int $type): array|false
    {
        return \Tests\Support\FakeDns::$dnsGetRecordResult;
    }

    function gethostbynamel(string $host): array|false
    {
        return \Tests\Support\FakeDns::$gethostbynamelResult;
    }
}
