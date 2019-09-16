<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * Class RequirementsController
 *
 * @package App\Http\Controllers\Setup
 */
class RequirementsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        [$success, $results] = $this->checkRequirements();

        return view('setup.requirements', [
            'success' => $success,
            'results' => $results,
        ]);
    }

    /**
     * @return array
     */
    protected function checkRequirements()
    {
        $results = [
            'php_version' => PHP_VERSION_ID >= 70200,
            'extension_bcmath' => extension_loaded('bcmath'),
            'extension_ctype' => extension_loaded('ctype'),
            'extension_json' => extension_loaded('json'),
            'extension_mbstring' => extension_loaded('mbstring'),
            'extension_openssl' => extension_loaded('openssl'),
            'extension_pdo_mysql' => extension_loaded('pdo_mysql'),
            'extension_tokenizer' => extension_loaded('tokenizer'),
            'extension_xml' => extension_loaded('xml'),
            'env_writable' => File::isWritable(base_path('.env')),
        ];

        $success = !in_array(false, $results);

        return [$success, $results];
    }
}
