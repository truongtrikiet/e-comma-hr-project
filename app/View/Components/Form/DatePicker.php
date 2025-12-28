<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public $id;
    public $name;
    public $value;
    public $label;
    public $placeholder;
    public $class;
    public $isRequired;
    public $disabled;
    public $oldName;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = null,
        $label = null,
        $placeholder = null,
        $class = null,
        $isRequired = null,
        $disabled = null,
        $oldName = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->isRequired = $isRequired;
        $this->disabled = $disabled;
        $this->oldName = $oldName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.date-picker');
    }
}
