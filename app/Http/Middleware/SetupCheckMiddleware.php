<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SetupCheckMiddleware
{
    public const GENERIC_APP_KEY = 'someRandomStringWith32Characters';

    /**
     * This middleware checks if LinkAce was correctly set up:
     * Is a proper app key set and was the setup completed correctly?
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.key') === self::GENERIC_APP_KEY) {
            $envContent = File::get(base_path('.env'));
            $envContent = preg_replace('/APP_KEY=(.*)\S/', 'APP_KEY=' . $this->generateRandomAppKey(), $envContent);
            File::put(base_path('.env'), $envContent);

            Log::warning('APP_KEY variable contained insecure standard value. New key was generated.');
            return redirect()->refresh();
        }

        $setupCompleted = setupCompleted();

        if ($request->is('setup/*')) {
            if ($setupCompleted) {
                // Do not allow access to the setup after it was completed
                return redirect()->route('front');
            }

            // Skip check if current route targets the setup
            return $next($request);
        }

        if (!$setupCompleted) {
            // Start the setup if it was not completed yet
            return redirect()->route('setup.welcome');
        }

        return $next($request);
    }

    protected function generateRandomAppKey(): string
    {
        return 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
    }
}
