<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class SubmitButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title, $target, $icon, $buttonClass;
    public function __construct($title, $target, $icon = null, $buttonClass = null)
    {
        $this->title = $title;
        $this->target = $target;
        $this->icon = $icon;
        $this->buttonClass = $buttonClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.submit-button');
    }
}
