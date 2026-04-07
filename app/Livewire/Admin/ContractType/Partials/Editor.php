<?php

namespace App\Livewire\Admin\ContractType\Partials;

use Livewire\Component;

class Editor extends Component
{
    public $initialData;

    public function mount($initialData = '')
    {
        $this->initialData = $initialData;
    }

    public function render()
    {
        return view('livewire.admin.contract-type.partials.editor');
    }
}
