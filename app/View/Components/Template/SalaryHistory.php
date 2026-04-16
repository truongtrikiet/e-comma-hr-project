<?php

namespace App\View\Components\Template;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SalaryHistory extends Component
{
    public $label;

    /**
     * Create a new component instance.
     */
    public function __construct($label = '')
    {
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.templates.salary-history');
    }
}
