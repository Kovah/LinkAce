<?php

namespace App\View\Components\Forms;

use App\Enums\ModelAttribute;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VisibilityToggle extends Component
{
    public function render(): View
    {
        $public = ModelAttribute::VISIBILITY_PUBLIC;
        $internal = ModelAttribute::VISIBILITY_INTERNAL;
        $private = ModelAttribute::VISIBILITY_PRIVATE;

        return view('components.forms.visibility-toggle', [
            'public' => $public,
            'internal' => $internal,
            'private' => $private,
            'publicSelected' => old('visibility') === $public
                || (old('visibility') === null && usersettings('links_default_visibility') === $public),
            'internalSelected' => old('visibility') === $internal
                || (old('visibility') === null && usersettings('links_default_visibility') === $internal),
            'privateSelected' => old('visibility') === $private
                || (old('visibility') === null && usersettings('links_default_visibility') === $private),
        ]);
    }
}
