<?php

namespace ISS\App\Presentation\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IssMessages extends Component
{
    public $issMessage;

    /**
     * Create a new component instance.
     */
    public function __construct($issMessage = '')
    {
        $this->issMessage = $issMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('iss::components.iss-messages', ['issMessage' => $this->issMessage]);
    }
}
