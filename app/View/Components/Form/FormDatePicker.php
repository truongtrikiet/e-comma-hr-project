<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FormDatePicker extends Component
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $isRequired;

    /**
     * @var string
     */
    public $maxDate;

    /**
     * @var string
     */
    public $minDate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = null, $id, $name, $placeholder = null, $value='', $isRequired = false, $maxDate = 'today',$minDate = '')
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->isRequired = $isRequired;
        $this->maxDate = $maxDate;
        $this->minDate = $minDate;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.form-date-picker');
    }
}
