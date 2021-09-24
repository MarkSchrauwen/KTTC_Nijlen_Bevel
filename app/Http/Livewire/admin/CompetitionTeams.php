<?php

namespace App\Http\Livewire\Admin;

use App\Models\CompetitionTeam;
use Livewire\Component;
use Livewire\WithPagination;

class CompetitionTeams extends Component
{
    use WithPagination;

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;

    /**
    * Put your custom public properties here!
    */
    public $name;

    /**
    * Validation rules
    *
    * @return void
    */
    public function rules(){
        return [
            "name" => 'required',
        ];
    }

    /**
    * Loads the model data of this component.
    *
    * @return void
    */
    public function loadModel(){
        $data = CompetitionTeam::find($this->modelId);
        // Assign the variables here
        $this->name = $data->name;
    }

    /**
    * The data for the model mapped in this component
    *
    * @return void
    */
    public function modelData(){
        return [
            "name" => $this->name,
        ];
    }

    /**
    * The create function
    *
    * @return void
    */
    public function create(){
        $this->validate();
        CompetitionTeam::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
    }

    /**
    * The read function
    *
    * @return void
    */
    public function read(){
        return CompetitionTeam::paginate(5);
    }

    /**
    * The update function
    *
    * @return void
    */
    public function update(){
        dd("Ik ben hier aangekomen");
        $this->validate();
        CompetitionTeam::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
    * The delete function
    *
    * @return void
    */
    public function delete(){
        CompetitionTeam::destroy($this->modelId);
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
        return view('livewire.admin.competition-teams',[
            'data' => $this->read(),
        ]);
    }
}