<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{
    /**
     * Create a new component instance.
     */
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $name,
        public $id = '',
        public $label = '',
        public $placeholder = '',
        public $value = '',
        public $oldName = '',
        public $type = 'text',
        public $isRequired = false,
        public $isShowEye = false
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.form-input');
    }
}
