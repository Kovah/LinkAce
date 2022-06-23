<?php

namespace App\View\Components\Forms;

use App\Enums\ModelAttribute;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VisibilityToggle extends Component
{
    public function __construct(private ?int $existingValue = null)
    {
    }

    public function render(): View
    {
        $public = ModelAttribute::VISIBILITY_PUBLIC;
        $internal = ModelAttribute::VISIBILITY_INTERNAL;
        $private = ModelAttribute::VISIBILITY_PRIVATE;

        return view('components.forms.visibility-toggle', [
            'public' => $public,
            'internal' => $internal,
            'private' => $private,
            'publicSelected' => old('visibility', $this->existingValue) === $public
                || (old('visibility', $this->existingValue) === null && usersettings('links_default_visibility') === $public),
            'internalSelected' => old('visibility', $this->existingValue) === $internal
                || (old('visibility', $this->existingValue) === null && usersettings('links_default_visibility') === $internal),
            'privateSelected' => old('visibility', $this->existingValue) === $private
                || (old('visibility', $this->existingValue) === null && usersettings('links_default_visibility') === $private),
        ]);
    }
}
