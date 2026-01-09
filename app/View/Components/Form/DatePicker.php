<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class DatePicker extends Component
{
    public $name;
    public $label;
    public $id;
    public $placeholder;
    public $value;
    public $oldName;
    public $type;
    public $isRequired;
    public $inputRounded;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $label = null,
        $id = null,
        $placeholder = '',
        $value = null,
        $oldName = null,
        $type = 'text',
        $isRequired = false,
        $inputRounded = true
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->id = $id ?: $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->oldName = $oldName;
        $this->type = $type;
        $this->isRequired = (bool) $isRequired;
        $this->inputRounded = (bool) $inputRounded;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.date-picker');
    }
}
