<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Submit extends Component
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

     /**
     * @var string
     */
    public $id;

    /**
     * Create a new component instance.
     */
    public function __construct($label = 'Submit', $type = 'primary', $id = '')
    {
        $this->label = $label;
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.submit');
    }
}
