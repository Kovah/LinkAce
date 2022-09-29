<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityLog;
use App\Enums\ApiToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateSystemApiTokenRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    public function index()
    {
        return view('admin.api-tokens.index', [
            'tokens' => User::getSystemUser()->tokens()->get(),
        ]);
    }

    public function show(PersonalAccessToken $token)
    {
        return view('admin.api-tokens.show', [
            'token' => $token,
        ]);
    }

    public function store(CreateSystemApiTokenRequest $request): RedirectResponse
    {
        $abilities = $request->validated('abilities');

        if ($request->get('private_access', false)) {
            $abilities[] = ApiToken::ABILITY_SYSTEM_ACCESS_PRIVATE;
        } else {
            $abilities[] = ApiToken::ABILITY_SYSTEM_ACCESS;
        }

        $token = User::getSystemUser()->createToken($request->validated('token_name'), $abilities);

        activity()
            ->by($request->user())
            ->withProperty('token_id', $token->accessToken->id)
            ->log(ActivityLog::SYSTEM_API_TOKEN_GENERATED);

        session()->flash('new_token', $token->plainTextToken);

        return redirect()->route('system.api-tokens.show', ['api_token' => $token->accessToken]);
    }

    public function destroy(Request $request, PersonalAccessToken $token): RedirectResponse
    {
        $this->authorize('delete', $token);

        $token->delete();

        activity()
            ->by($request->user())
            ->log(ActivityLog::SYSTEM_API_TOKEN_REVOKED);

        flash()->warning(trans('auth.api_tokens.revoke_successful'));
        return redirect()->route('system.api-tokens.index');
    }
}
