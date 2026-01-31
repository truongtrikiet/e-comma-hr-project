<?php

namespace App\View\Components\Custom;

use Illuminate\View\Component;

class Summernote extends Component
{
    public $name;
    public $id;
    public $value;
    public $label;
    public $height;
    public $placeholder;

    public function __construct($name = 'content', $id = null, $value = null, $label = null, $height = 200, $placeholder = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
        $this->label = $label;
        $this->height = $height;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.custom.summernote');
    }
}
