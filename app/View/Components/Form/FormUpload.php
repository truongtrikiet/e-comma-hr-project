<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FormUpload extends Component
{
    public $id;
    public $label;
    public $name;
    public $multiple;
    public $isRequired;
    public $value;
    public $previewId;

    public function __construct(
        $id = 'sDefaultUpload',
        $label = null,
        $name = 'default_upload',
        $multiple = false,
        $isRequired = false,
        $value = null,
        $previewId = null,
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->multiple = $multiple;
        $this->isRequired = $isRequired;
        $this->value = $value;
        $this->previewId = $previewId;
        if ($value && str_contains($value, '/storage/')) {
            $value = str_replace(url('/storage'), url('/cors-image'), $value);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.form-upload');
    }
}
