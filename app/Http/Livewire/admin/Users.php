<?php

namespace App\Http\Livewire\Admin;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Users extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // Modal variables
    public $modalConfirmDeleteVisible = false;
    public $modalFormVisible = false;

    // Model variables
    public $modelId;
    public $allRoles;
    public $role_search;
    public $roleSearch;
    public $last_name_search = "";
    public $lastNameSearch = "";
    public $roleName;
    public $connectedMember;
    public $oldConnectedMember = "";
    public $allMembers;
    public $userFirstName;
    public $userLastName;
    public $userEmail;

    public function rules(){
        return [
            "roleName"=> 'required',
            "connectedMember" => 'required',
        ];
    }
    
    /**
     * Livewire mount function
     *
     * @return void
     */
    public function mount() {
        // Resets the pagination after reloading the page
        $this->resetPage();
        $this->getMembers();
        $this->getRoleNames();
    }

    public function updatedConnectedMember() {
        if($this->connectedMember == "" || $this->connectedMember == null) {
            $this->roleName = "User";
        } else {
            if($this->roleName == "User") {
                $this->roleName = "Member";
            }
        }
        $this->getMembers();
        $this->getRoleNames();
    }

    public function updatedRoleName() {
        $this->getMembers();
        $this->getRoleNames();
    }

    /**
    * Loads the model data of this component.
    *
    * @return void
    */
    public function loadModel(){
        $data = User::with('member')->find($this->modelId);
        // Assign the variables here
        $this->userFirstName = $data->firstname;
        $this->userLastName = $data->lastname;
        $this->userEmail = $data->email;
        if($data->role_id == Role::isUser) {
            $this->connectedMember = "";
        } else {
            $this->connectedMember = User::find($this->modelId)->member->name;
            $this->oldConnectedMember = $this->connectedMember;
        }
        
        $this->roleName = Role::find($data->role_id)->name;
    }

    public function modelData(){
        $role = Role::where('name','LIKE',$this->roleName)->first();
        if($role->id == 4 || $role->id == 5) {
            return [
                "isAdmin" => 1,
                "role_id" => $role->id,
            ];            
        } else {
            return [
                "isAdmin" => 0,
                "role_id" => $role->id,
            ];
        }
    }

    public function getMembers() {
        $this->allMembers = Member::select('name')->get()->unique('name');
    }

    public function getRoleNames() {
        $this->allRoles = Role::select('name')->get()->unique('name');
    }

    public function resetOnlyLivewireVariables() {
        $this->reset();
        $this->getMembers();
        $this->getRoleNames();
    }

    public function read() {
        $this->authorize('view',User::class);
        if($this->roleSearch != null || $this->roleSearch !="") {
            $users = User::orderBy('lastname','Asc')
            ->where('role_id',$this->roleSearch)
            ->where('lastname','LIKE','%'.$this->lastNameSearch.'%')
            ->with('role')
            ->paginate(11);            
        } else {
            $users = User::orderBy('lastname','Asc')
            ->where('lastname','LIKE','%'.$this->lastNameSearch.'%')
            ->with('role')
            ->paginate(11);
        }
        foreach($users as $user) {
            if(User::find($user->id)->member != null) {
                $user->memberName = User::find($user->id)->member->name;                
            } else {$user->memberName = "";}
        }
        return $users;
    }

    public function update() {
        $this->authorize('update',User::class);
        // user can't update it's own specifications ELSE we generate error
        if($this->modelId != auth()->user()->id) {

            // if option none = no Connected Member is chosen then clear user_id in Member-model
            // and reset role to User
            if($this->connectedMember == "" || $this->connectedMember == null ) {
                $this->roleName = "User";
                User::find($this->modelId)->update($this->modelData());
                Member::where('name','LIKE',$this->oldConnectedMember)->update(['user_id'=> null]);
                $this->oldConnectedMember = $this->connectedMember;

            // if Connected Member was chosen we have to check if we are not going to overwrite
            // another Connected Member
            } else {
                $memberUserId = Member::where('name','LIKE',$this->connectedMember)->first()->user_id;
                if($memberUserId != null) {
                    
                    // there is already a user_id present so we have to check if connected Member
                    // is the same as the user selected to be updated ELSE we send error
                    if($memberUserId == $this->modelId) {
                        User::find($this->modelId)->update($this->modelData());                    
                    } else {
                        session()->flash("userError","You can't select a member that is already connected to another user !");
                    }

                // The Connected Member was still free (null) so we can load User_id into the Member-model
                } else {
                        User::find($this->modelId)->update($this->modelData());
                        Member::where('name','LIKE',$this->connectedMember)->update(['user_id'=> $this->modelId]);
                        Member::where('name','LIKE',$this->oldConnectedMember)->update(['user_id'=> null]);
                        $this->oldConnectedMember = $this->connectedMember;
                }                
            }
                
        } else {
            session()->flash("userError","You can't update your own role or connected member !");
        }
        $this->modalFormVisible = false;
        $this->resetPage(); 
        return;
    }

    public function delete() {
        $this->authorize('delete',User::class);
        if($this->modelId != auth()->user()->id) {
            Member::where('user_id',$this->modelId)->first()->update(["user_id" => null]);
            User::destroy($this->modelId);            
        } else {
            session()->flash("userError","You can't delete your own user registration !");
        }
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

        /**
     * Function shows Update Modal
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id) {
        $this->resetOnlyLivewireVariables();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
        }

    public function searchSubmit() {
        if($this->role_search == null || $this->role_search == "") {
            $this->roleSearch = $this->role_search;
        } else {
            $this->roleSearch = Role::where('name','LIKE',$this->role_search)->first()->id;            
        }
        $this->lastNameSearch = $this->last_name_search;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.user',[
            'data' => $this->read(),
        ]);
    }
}
