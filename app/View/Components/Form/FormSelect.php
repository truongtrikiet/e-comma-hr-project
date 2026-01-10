<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelect extends Component
{
	public function __construct(
		public $name,
		public $label = null,
		public $id = null,
		public $oldName = null,
		public $isRequired = false,
		public $multiple = false,
		public $dataValues = null,
		public $selectValueAttribute = 'value',
		public $selectValueLabel = 'label',
		public $placeholder = null,
		public $selected = null
	) {
		$this->id = $this->id ?: $this->name;
	}

	public function render(): View|Closure|string
	{
		$isMultiple = ($this->multiple === true || $this->multiple === 'true');
		$selectName = $this->name . ($isMultiple ? '[]' : '');
		$valueKey = $this->selectValueAttribute ?? 'value';
		$labelKey = $this->selectValueLabel ?? 'label';
		$current = old($this->oldName ?: $this->name, $this->selected ?? null);

		return view('components.form.form-select', compact(
			'isMultiple',
			'selectName',
			'current',
			'valueKey',
			'labelKey'
		))->with([
			'dataValues' => $this->dataValues,
			'placeholder' => $this->placeholder,
		]);
	}
}
