<?php

namespace App\Http\Livewire\Admin;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateMemberNotification;
use App\Notifications\DeleteMemberNotification;
use App\Notifications\UpdateMemberNotification;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Notification;

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
    public $old_user_id;
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
        $this->old_user_id = $data->user_id;
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

    public function resetOnlyLivewireVariables() {
        $this->reset();
        $this->getCompetitionNames();
        $this->getTeamNames();
        $this->getMembers();
    }

    /**
    * The create function
    *
    * @return void
    */
    public function create(){
        $this->authorize('create',Member::class);
        $this->validate();
        // we need to check if selected to connect user is already connected or not
        $member = Member::where('user_id',$this->user_id)->first();
        if($this->user_id != null && $member != null) {
            session()->flash('memberError','You are not allowed to connect a user that is already linked to another member !');
        } else {
            $member = Member::create($this->modelData());
            // if user was chosen to connect then set role to Member
            if($this->user_id != null) {
                User::find($this->user_id)->update(["isAdmin" => "0", "role_id" => Role::isMember]);
                $connectedUser = User::find($this->user_id);                
            } else {
                $connectedUser = null;
            }
            $admins = User::where('role_id', Role::isSiteAdmin)->get();
            Notification::send($admins, new CreateMemberNotification(auth()->user(), $member, $connectedUser));
            session()->flash('memberSuccess','You succesfully created a new member !');           
        }
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
        return Member::orderBy('name','Asc')->paginate(11);
    }

    /**
    * The update function
    *
    * @return void
    */
    public function update(){
        $this->authorize('update',Member::class);
        $this->validate();
        // we have to check if selected user to connect
        // 1) has changed and if so 
        // 2) we need to eliminate that user can change his own connected user
        // 3) then we need to check if user isn't already connected
        // then we need to reset isAdmin to 0 and role to isUser
        if($this->user_id == $this->old_user_id) {
            Member::find($this->modelId)->update($this->modelData());
            $member = Member::find($this->modelId);
            $connectedUser = User::find($this->user_id);
            $admins = User::where('role_id', Role::isSiteAdmin)->get();
            Notification::send($admins, new UpdateMemberNotification(auth()->user(), $member, $connectedUser));
            session()->flash("memberSuccess","You have succesfully updated a member !");
        } else {
            if($this->old_user_id == auth()->user()->id) {
                session()->flash("memberError","You can't link another User for yourself !");
            } else {
                // if no User was selected then we update member and
                // and set role of old user to User
                if($this->user_id == "" || $this->user_id == null) {
                    $this->user_id = null;
                    Member::find($this->modelId)->update($this->modelData());
                    $member = Member::find($this->modelId);
                    $connectedUser = null;
                    if($this->old_user_id != null || $this->old_user_id != null) {
                        User::find($this->old_user_id)->update(["isAdmin" => "0", "role_id" => Role::isUser]);
                    }
                    $this->old_user_id = $this->user_id;
                    $admins = User::where('role_id', Role::isSiteAdmin)->get();
                    Notification::send($admins, new UpdateMemberNotification(auth()->user(), $member, $connectedUser));
                    session()->flash("userSuccess","You have successfully updated a member !");
                } else {
                    $alreadyExistingMember = Member::where('user_id',$this->user_id)->first();
                    if($alreadyExistingMember != null) {
                        session()->flash("memberError","chosen User to connect is already linked to another User !");
                    } else {
                        // update Member and
                        // 1- update role of connected User to Member
                        // 2- update role of old connected User to User
                        Member::find($this->modelId)->update($this->modelData());
                        $member = Member::find($this->modelId);
                        User::find($this->user_id)->update(["isAdmin" => "0", "role_id" => Role::isMember]);
                        if($this->old_user_id != null || $this->old_user_id != null) {
                            User::find($this->old_user_id)->update(["isAdmin" => "0", "role_id" => Role::isUser]);
                        }
                        $this->old_user_id = $this->user_id;
                        $connectedUser = User::find($this->user_id);
                        $admins = User::where('role_id', Role::isSiteAdmin)->get();
                        Notification::send($admins, new UpdateMemberNotification(auth()->user(), $member, $connectedUser));
                        session()->flash("userSuccess","You have successfully updated a member !");
                    }                    
                }               
            }
        }        
        $this->modalFormVisible = false;
    }

    /**
    * The delete function
    *
    * @return void
    */
    public function delete(){
        $this->authorize('delete',Member::class);
        
        $member = Member::find($this->modelId);
        $memberUserId = $member->user_id;
        // don't allow Admin to delete his own membership
        if($memberUserId == auth()->user()->id) {
            session()->flash("memberError","You can't delete your own membership !");
        } else {
            // delete Member and change user(isAdmin to 0, role_id to 1)
            if(Member::find($this->modelId)->user_id) {
                $userId = Member::find($this->modelId)->user_id;
                User::find($userId)->update(["isAdmin" => "0", "role_id" => Role::isUser]);
            }
            Member::destroy($this->modelId);
            $admins = User::where('role_id', Role::isSiteAdmin)->get();
            Notification::send($admins, new DeleteMemberNotification(auth()->user(), $member));            
        }

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
    public function getUsers() {
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
            'users' => $this->getUsers(),
        ]);
    }
}