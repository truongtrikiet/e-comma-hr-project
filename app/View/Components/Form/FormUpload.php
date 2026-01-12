<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FormUpload extends Component
{
    /**
     * @var mixed|string
     */
    public $id;

    /**
     * @var mixed|string
     */
    public $label;

    /**
     * @var mixed|string
     */
    public $name;

    /**
     * @var mixed|string
     */
    public $multiple;

    /**
     * @var mixed|boolean
     */
    public $isRequired;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $id = 'sDefaultUpload',
        $label = null,
        $name = 'default_upload',
        $multiple = false,
        $isRequired = false
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->multiple = $multiple;
        $this->isRequired = $isRequired;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.form-upload');
    }
}
