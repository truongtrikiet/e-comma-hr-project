<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Datatable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id = 'datatable-id',
        public string $title = '',
        public string $tableClass = 'display',
        public string $tableStyle = 'min-width: 845px',
        public array $menuLength = [7, 10, 20, 50],
        public int $pageLength = 50,
        public ?string $customScript = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.datatable');
    }
}
