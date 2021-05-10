<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class RequirementsController extends Controller
{
    /**
     * Display all requirements that must be fulfilled to run the setup.
     *
     * @return View
     */
    public function index(): View
    {
        [$success, $results] = $this->checkRequirements();

        return view('setup.requirements', [
            'success' => $success,
            'results' => $results,
        ]);
    }

    protected function checkRequirements(): array
    {
        $results = [
            'php_version' => PHP_VERSION_ID >= 70300,
            'extension_bcmath' => extension_loaded('bcmath'),
            'extension_ctype' => extension_loaded('ctype'),
            'extension_json' => extension_loaded('json'),
            'extension_mbstring' => extension_loaded('mbstring'),
            'extension_openssl' => extension_loaded('openssl'),
            'extension_pdo_mysql' => extension_loaded('pdo_mysql'),
            'extension_tokenizer' => extension_loaded('tokenizer'),
            'extension_xml' => extension_loaded('xml'),
            'env_writable' => File::isWritable(base_path('.env')),
            'storage_writable' => File::isWritable(storage_path()) && File::isWritable(storage_path('logs')),
        ];

        $success = !in_array(false, $results, true);

        return [$success, $results];
    }
}
