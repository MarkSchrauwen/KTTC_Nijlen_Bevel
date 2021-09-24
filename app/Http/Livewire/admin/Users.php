<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    // Modal variables
    public $modalConfirmDeleteVisible = false;

    // Model variables
    public $modelId;
    
    /**
     * Livewire mount function
     *
     * @return void
     */
    public function mount() {
        // Resets the pagination after reloading the page
        $this->resetPage();
    }

    public function read() {
        return User::select('id','firstname','lastname','email')->paginate(5);
    }

    public function delete() {
        User::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }
    
    /**
     * Function shows Delete Modal
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id) {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    public function render()
    {
        return view('livewire.admin.user',[
            'data' => $this->read(),
        ]);
    }
}
