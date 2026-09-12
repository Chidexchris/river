<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Captcha extends Component
{
    public mixed $path;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($path = null)
    {
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        if ($this->path) return view("$this->path.captcha");

        return view('partials.captcha');
    }
}
