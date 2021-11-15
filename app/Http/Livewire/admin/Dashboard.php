<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Withpagination;

class Dashboard extends Component
{
    use Withpagination;

    public function mount() {
        $this->firstname = auth()->user()->firstname;
        $this->notifications = auth()->user()->unreadNotifications;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
