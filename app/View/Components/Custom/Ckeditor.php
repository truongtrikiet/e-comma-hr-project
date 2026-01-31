<?php

namespace App\View\Components\Custom;

use Illuminate\View\Component;

class Ckeditor extends Component
{
    public $name;
    public $id;
    public $value;
    public $label;
    public $rows;

    public function __construct($name = 'content', $id = null, $value = null, $label = null, $rows = 8)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
        $this->label = $label;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.custom.ckeditor');
    }
}
