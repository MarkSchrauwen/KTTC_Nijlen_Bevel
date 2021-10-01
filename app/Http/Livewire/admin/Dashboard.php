<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount() {
        $this->firstname = auth()->user()->firstname;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
