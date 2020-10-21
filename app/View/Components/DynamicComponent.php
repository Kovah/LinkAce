<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DynamicComponent extends Component
{
    private $component;

    /**
     * Create a new component instance.
     *
     * @param $component
     */
    public function __construct($component)
    {
        $this->component = $component;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        if (!view()->exists('components.' . $this->component)) {
            return "<!-- Component $this->component could not be found! -->";
        }

        return view('components.' . $this->component, ['attributes' => $this->attributes]);
    }
}
