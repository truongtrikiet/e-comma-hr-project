<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class FormSelectMultiple extends Component
{
	public $name;
	public $id;
	public $label;
	public $dataValues;
	public $selected;
	public $placeholder;
	public $attributes;
	public $oldName;
	public $isRequired;
	public $selectValueAttribute;
	public $selectValueLabel;
	public $tags;
    public $selectName;
    public $classes;

	/**
	 * Create the component instance.
	 *
	 * @return void
	 */
	public function __construct($name, $id = null, $dataValues = [], $selected = [], $placeholder = '', $attributes = null, $oldName = null, $isRequired = false, $selectValueAttribute = 'value', $selectValueLabel = 'label', $tags = true, $label = null)
	{
		$this->name = $name ?? 'tags';
		$this->id = $id ?? $this->name;
		$this->label = $label;
		$this->dataValues = $dataValues ?? [];
		$this->selected = $this->normalizeSelected($selected);
		$this->placeholder = $placeholder ?? '';

		$this->attributes = ($attributes instanceof ComponentAttributeBag)
			? $attributes
			: new ComponentAttributeBag($attributes ?? []);

		$this->oldName = $oldName ?? null;
		$this->isRequired = filter_var($isRequired, FILTER_VALIDATE_BOOLEAN);
		$this->selectValueAttribute = $selectValueAttribute;
		$this->selectValueLabel = $selectValueLabel;
		$this->tags = filter_var($tags, FILTER_VALIDATE_BOOLEAN);

		$this->selectName = $this->name . '[]';
		$this->classes = trim('form-control input-default js-multi-select');
	}

	/**
	 * Normalize the selected value into an array.
	 *
	 * @param mixed $selected
	 * @return array
	 */
	protected function normalizeSelected($selected)
	{
		if (is_null($selected)) {
			return [];
		}

		if (is_string($selected)) {
			return $selected === '' ? [] : explode(',', $selected);
		}

		if (!is_array($selected)) {
			return (array) $selected;
		}

		return $selected;
	}

	/**
	 * Helper to return the HTML name attribute for a multiple select.
	 *
	 * @return string
	 */
	public function selectName()
	{
		return $this->selectName;
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\View\View|string
	 */
	public function render()
	{
		return view('components.form.form-select-multiple', [
			'label' => $this->label,
			'name' => $this->name,
			'id' => $this->id,
			'dataValues' => $this->dataValues,
			'selected' => $this->selected,
			'placeholder' => $this->placeholder,
			'attributes' => $this->attributes,
			'oldName' => $this->oldName,
			'isRequired' => $this->isRequired,
			'selectValueAttribute' => $this->selectValueAttribute,
			'selectValueLabel' => $this->selectValueLabel,
			'tags' => $this->tags,
			'selectName' => $this->selectName,
			'classes' => $this->classes,
		]);
	}
}
