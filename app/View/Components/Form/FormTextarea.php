<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormTextarea extends Component
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
     * @var mixed|string
     */
    public $oldName;

    /**
     * @var bool
     */
    public $isRequired;

    /**
     * @var int
     */
    public $rows;

    /**
     * Create a new component instance.
     */
    public function __construct($label, $id, $name, $placeholder = '', $value = '', $oldName = '', $isRequired = false, $rows = 3)
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->oldName = $oldName;
        $this->isRequired = $isRequired;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.form-textarea');
    }
}
