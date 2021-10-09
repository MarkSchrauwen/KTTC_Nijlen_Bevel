<?php

namespace App\Http\Livewire\Admin;

use App\Models\BoardMember;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BoardMembers extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;

    /**
    * Put your custom public properties here!
    */
    public $name;
    public $title;
    public $address;
    public $postcode;
    public $city;
    public $phone;
    public $mobile;

    /**
    * Validation rules
    *
    * @return void
    */
    public function rules(){
        return [
            "name" => "required",
            "title" => "required",
        ];
    }

    /**
    * Loads the model data of this component.
    *
    * @return void
    */
    public function loadModel(){
        $data = BoardMember::find($this->modelId);
        $this->name = $data->name;
        $this->title = $data->title;
        $this->address = $data->address;
        $this->postcode = $data->postcode;
        $this->city = $data->city;
        $this->phone = $data->phone;
        $this->mobile = $data->mobile;
    }

    /**
    * The data for the model mapped in this component
    *
    * @return void
    */
    public function modelData(){
        return [
            "name" => $this->name,
            "title" => $this->title,
            "address" => $this->address,
            "postcode" => $this->postcode,
            "city" => $this->city,
            "phone" => $this->phone,
            "mobile" => $this->mobile,
        ];
    }

    /**
    * The create function
    *
    * @return void
    */
    public function create(){
        $this->authorize('create',BoardMember::class);
        $this->validate();
        BoardMember::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
    }

    /**
    * The read function
    *
    * @return void
    */
    public function read(){
        $this->authorize('viewAny',BoardMember::class);
        return BoardMember::paginate(11);
    }

    /**
    * The update function
    *
    * @return void
    */
    public function update(){
        $this->authorize('update',BoardMember::class);
        $this->validate();
        BoardMember::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
    * The delete function
    *
    * @return void
    */
    public function delete(){
        $this->authorize('delete',BoardMember::class);
        BoardMember::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
    * Function to show the create Modal
    *
    * @return void
    */
    public function createShowModal(){
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
    }

    /**
    * Function to show the update Modal
    *
    * @return void
    */
    public function updateShowModal($id){
        $this->resetValidation();
        $this->reset();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    /**
    * Function to show the delete Modal
    *
    * @return void
    */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    /**
    * The render function
    *
    * @return void
    */
    public function render()
    {
        return view('livewire.admin.board-members',[
            'data' => $this->read(),
        ]);
    }
}