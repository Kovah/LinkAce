<?php

namespace App\Console\Commands;

use App\Http\Middleware\TrustHosts;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DebugConfigCommand extends Command
{
    protected $signature = 'debug';

    protected $description = 'Output debug information to help diagnose configuration issues. Only available when APP_DEBUG=true.';

    public function handle(): int
    {
        if (!config('app.debug')) {
            $this->error('This command is only available when APP_DEBUG=true.');
            return self::FAILURE;
        }

        $this->line('');
        $this->line('<fg=cyan;options=bold>╔══════════════════════════════════════╗</>');
        $this->line('<fg=cyan;options=bold>║     LinkAce Debug Configuration      ║</>');
        $this->line('<fg=cyan;options=bold>╚══════════════════════════════════════╝</>');
        $this->line('');

        $this->printApplicationInfo();
        $this->printTrustedHostsInfo();
        $this->printTrustedProxiesInfo();
        $this->printDatabaseInfo();
        $this->printSystemRequirements();

        return self::SUCCESS;
    }

    private function resolveVersion(): string
    {
        try {
            $package = json_decode(Storage::disk('root')->get('package.json'), false);
            return isset($package->version) ? 'v' . $package->version : '<fg=yellow>unknown</>';
        } catch (Exception) {
            return '<fg=yellow>unknown</>';
        }
    }

    private function printApplicationInfo(): void
    {
        $this->line('<options=bold>Application</>');
        $this->table([], [
            ['LinkAce Version', $this->resolveVersion()],
            ['Laravel Version', app()->version()],
            ['PHP Version', PHP_VERSION],
            ['Environment', config('app.env')],
            ['Debug Mode', config('app.debug') ? '<fg=yellow>true</>' : 'false'],
        ]);
    }

    private function printTrustedHostsInfo(): void
    {
        $this->line('<options=bold>Trusted Hosts</>');

        $appUrl = config('app.url');
        $trustHosts = new TrustHosts(app());
        $patterns = $trustHosts->hosts();

        $rows = [
            ['APP_URL', $appUrl],
            ['Allowed host pattern', implode(', ', array_filter($patterns))],
        ];

        if ($appUrl === 'http://localhost') {
            $rows[] = ['', '<fg=red>⚠ APP_URL is set to the default "http://localhost".</>'];
            $rows[] = ['', '<fg=red>  Any request from a different host will be rejected with 400.</>'];
        } elseif (!str_starts_with($appUrl, 'https://')) {
            $rows[] = ['', '<fg=yellow>⚠ APP_URL uses http:// but users may be accessing via https://.</>'];
            $rows[] = ['', '<fg=yellow>  Requests with a different scheme in the Host header may be blocked.</>'];
        }

        $this->table([], $rows);
    }

    private function printTrustedProxiesInfo(): void
    {
        $this->line('<options=bold>Trusted Proxies</>');

        $proxies = config('app.trusted_proxies');

        $headerMap = [
            Request::HEADER_X_FORWARDED_FOR => 'X-Forwarded-For',
            Request::HEADER_X_FORWARDED_HOST => 'X-Forwarded-Host',
            Request::HEADER_X_FORWARDED_PORT => 'X-Forwarded-Port',
            Request::HEADER_X_FORWARDED_PROTO => 'X-Forwarded-Proto',
            Request::HEADER_X_FORWARDED_AWS_ELB => 'X-Forwarded-* (AWS ELB)',
        ];

        $trustedHeaders = array_values(array_filter(
            $headerMap,
            fn($bit) => (
                (Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB) & $bit
            ) === $bit,
            ARRAY_FILTER_USE_KEY
        ));

        $rows = [
            ['TRUSTED_PROXIES', $proxies ?? '<fg=yellow>null (none trusted)</>'],
            ['Trusted headers', implode(', ', $trustedHeaders)],
        ];

        if ($proxies === '*') {
            $rows[] = ['', '<fg=yellow>⚠ All proxies are trusted (TRUSTED_PROXIES=*).</>'];
            $rows[] = ['', '<fg=yellow>  X-Forwarded-Host sent by any upstream proxy is accepted and</>'];
            $rows[] = ['', '<fg=yellow>  validated against the Trusted Hosts pattern above.</>'];
            $rows[] = ['', '<fg=yellow>  If your proxy forwards an unexpected host, requests will fail.</>'];
        } elseif ($proxies === null) {
            $rows[] = ['', '<fg=yellow>⚠ No proxies are trusted. If you are behind a reverse proxy,</>'];
            $rows[] = ['', '<fg=yellow>  set TRUSTED_PROXIES to your proxy IP or subnet.</>'];
        }

        $this->table([], $rows);
    }

    private function printDatabaseInfo(): void
    {
        $this->line('<options=bold>Database</>');

        $connection = config('database.default');
        $dbConfig = config('database.connections.' . $connection);

        $rows = [
            ['DB_CONNECTION', $connection],
        ];

        if ($connection === 'sqlite') {
            $rows[] = ['DB_DATABASE', $dbConfig['database'] ?? 'n/a'];
        } else {
            $rows[] = ['DB_HOST', $dbConfig['host'] ?? 'n/a'];
            $rows[] = ['DB_PORT', $dbConfig['port'] ?? 'n/a'];
            $rows[] = ['DB_DATABASE', $dbConfig['database'] ?? 'n/a'];
        }

        $this->table([], $rows);
    }

    private function printSystemRequirements(): void
    {
        $this->line('<options=bold>System Requirements</>');

        $ok = '<fg=green>✔ OK</>';
        $fail = '<fg=red>✘ FAIL</>';

        $phpOk = PHP_VERSION_ID >= 80110;

        $extensions = [
            'bcmath', 'ctype', 'curl', 'dom', 'fileinfo',
            'filter', 'hash', 'json', 'mbstring', 'openssl',
            'pcre', 'session', 'tokenizer', 'xml',
        ];

        $dbExtensions = ['pdo_mysql', 'pdo_pgsql', 'pdo_sqlite'];

        $rows = [['PHP >= 8.1.10', $phpOk ? $ok : $fail . ' (found ' . PHP_VERSION . ')']];

        foreach ($extensions as $ext) {
            $rows[] = ['ext-' . $ext, extension_loaded($ext) ? $ok : $fail];
        }

        $rows[] = ['— Database drivers —', ''];
        foreach ($dbExtensions as $ext) {
            $rows[] = ['ext-' . $ext, extension_loaded($ext) ? $ok : '<fg=yellow>not loaded</>'];
        }

        $rows[] = ['— Filesystem —', ''];
        $rows[] = ['.env writable', File::isWritable(base_path('.env')) ? $ok : $fail];
        $rows[] = ['storage/ writable', File::isWritable(storage_path()) ? $ok : $fail];
        $rows[] = ['storage/logs writable', File::isWritable(storage_path('logs')) ? $ok : $fail];

        $this->table([], $rows);
    }
}
