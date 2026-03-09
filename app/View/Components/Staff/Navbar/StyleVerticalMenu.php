<?php

namespace App\View\Components\Staff\Navbar;

use Illuminate\View\Component;

class StyleVerticalMenu extends Component
{
    /**
     * The title.
     *
     * @var string
     */
    public $classes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.staff.navbar.style-vertical-menu');
    }
}
