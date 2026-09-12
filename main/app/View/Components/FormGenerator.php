<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FormGenerator extends Component
{
    public mixed $formClassName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($formClassName = null)
    {
        $this->formClassName = $formClassName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.formGenerator');
    }
}
