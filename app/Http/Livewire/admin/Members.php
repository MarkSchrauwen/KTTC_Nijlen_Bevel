<?php

namespace App\Http\Livewire\Admin;

use App\Models\Member;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Members extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;

    /**
    * Put your custom public properties here!
    */
    public $user_id;
    public $name;
    public $email;
    public $address;
    public $postal_code;
    public $city;
    public $phone;
    public $mobile;
    public $birthdate;

    /**
    * Validation rules
    *
    * @return void
    */
    public function rules(){
        return [
            "name" => "required",
            "email" => "email|nullable",
            "birthdate" => "date|nullable",
        ];
    }

    /**
    * Loads the model data of this component.
    *
    * @return void
    */
    public function loadModel(){
        $data = Member::find($this->modelId);
        // Assign the variables here
        $this->user_id = $data->user_id;
        $this->name = $data->name;
        $this->email = $data->email;
        $this->address = $data->address;
        $this->postal_code = $data->postal_code;
        $this->city = $data->city;
        $this->phone = $data->phone;
        $this->mobile = $data->mobile;
        $this->birthdate = $data->birthdate;
    }

    /**
    * The data for the model mapped in this component
    *
    * @return void
    */
    public function modelData(){
        return [
            "user_id" => $this->user_id,
            "name" => $this->name,
            "email" => $this->email,
            "address" => $this->address,
            "postal_code" => $this->postal_code,
            "city" => $this->city,
            "phone" => $this->phone,
            "mobile" => $this->mobile,
            "birthdate" => $this->birthdate,
        ];
    }

    /**
    * The create function
    *
    * @return void
    */
    public function create(){
        $this->authorize('create',Member::class);
        $this->validate();
        Member::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
    }

    /**
    * The read function
    *
    * @return void
    */
    public function read(){
        $this->authorize('view',Member::class);
        return Member::paginate(5);
    }

    /**
    * The update function
    *
    * @return void
    */
    public function update(){
        $this->authorize('update',Member::class);
        $this->validate();
        Member::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
    * The delete function
    *
    * @return void
    */
    public function delete(){
        $this->authorize('delete',Member::class);
        Member::destroy($this->modelId);
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
     * get all Users to select one to link to member
     *
     * @return void
     */
    public function allUsers() {
        return User::select('id', 'firstname', 'lastname')->get();
    }

    /**
    * The render function
    *
    * @return void
    */
    public function render()
    {
        return view('livewire.admin.members',[
            'data' => $this->read(),
            'users' => $this->allUsers(),
        ]);
    }
}