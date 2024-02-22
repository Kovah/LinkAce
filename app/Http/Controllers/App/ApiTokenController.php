<?php

namespace App\Http\Controllers\App;

use App\Enums\ActivityLog;
use App\Enums\ApiToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateApiTokenRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        return view('app.api-tokens.index', [
            'tokens' => $request->user()->tokens()->get(),
        ]);
    }

    public function store(CreateApiTokenRequest $request): RedirectResponse
    {
        $token = $request->user()->createToken($request->validated('token_name'), [ApiToken::ABILITY_USER_ACCESS]);

        activity()
            ->by($request->user())
            ->withProperty('token_id', $token->accessToken->id)
            ->log(ActivityLog::USER_API_TOKEN_GENERATED);

        return redirect()->route('api-tokens.index')->with('new_token', $token->plainTextToken);
    }

    public function destroy(Request $request, PersonalAccessToken $token): RedirectResponse
    {
        $this->authorize('delete', $token);

        $token->delete();

        activity()
            ->by($request->user())
            ->log(ActivityLog::USER_API_TOKEN_REVOKED);

        flash()->warning(trans('auth.api_tokens.revoke_successful'));
        return redirect()->route('api-tokens.index');
    }
}
