<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class RequirementsController extends Controller
{
    public function index(): View
    {
        [$success, $results] = $this->checkRequirements();

        return view('setup.requirements', [
            'pageTitle' => trans('setup.setup_requirements'),
            'success' => $success,
            'results' => $results,
        ]);
    }

    protected function checkRequirements(): array
    {
        $results = [
            'php_version' => PHP_VERSION_ID >= 80110,
            'extension_bcmath' => extension_loaded('bcmath'),
            'extension_ctype' => extension_loaded('ctype'),
            'extension_curl' => extension_loaded('curl'),
            'extension_dom' => extension_loaded('dom'),
            'extension_fileinfo' => extension_loaded('fileinfo'),
            'extension_filter' => extension_loaded('filter'),
            'extension_hash' => extension_loaded('hash'),
            'extension_json' => extension_loaded('json'),
            'extension_mbstring' => extension_loaded('mbstring'),
            'extension_openssl' => extension_loaded('openssl'),
            'extension_pcre' => extension_loaded('pcre'),
            'extension_session' => extension_loaded('session'),
            'extension_tokenizer' => extension_loaded('tokenizer'),
            'extension_xml' => extension_loaded('xml'),
        ];

        $dbResults = [
            'extension_pdo_mysql' => extension_loaded('pdo_mysql'),
            'extension_pdo_pgsql' => extension_loaded('pdo_pgsql'),
            'extension_pdo_sqlite' => extension_loaded('pdo_sqlite'),
        ];

        $additionalResults = [
            'env_writable' => File::isWritable(base_path('.env')),
            'storage_writable' => File::isWritable(storage_path()) && File::isWritable(storage_path('logs')),
        ];

        $success = !in_array(false, $results, true) && !in_array(false, $additionalResults, true);

        return [$success, array_merge($results, $dbResults, $additionalResults)];
    }
}
