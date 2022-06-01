<?php

namespace App\View\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Error extends Component
{

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function render(): View
    {
        return view('components.dashboard.error');
    }
}
