<div class="p-6">
    <div class="flex flex-row items-center justify-end px-4 py-3 text-left sm:px-6">
        <div class="m-2">
            <label class="text-sm">{{ __('Last Name') }}</label>
            <input type="text" wire:model="last_name_search">
        </div>
        <div class="m-2">
            <label class="text-sm">{{ __('Roles') }}</label>
            <select wire:ignore wire:model="role_search" class="block w-full text-sm appearance-none 
            bg-gray-100 border border-gray-300 text-gray-700 mx-1 py-1 px-2 pr-8 rounded round leading-tight 
            focus:outline-none focus:bg-white focus:border-gray-500">
            <option value="">{{ __('All') }}</option>
                @foreach($allRoles as $role)
                    <option value="{{ $role->name}}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <x-jet-success-button wire:click="searchSubmit">
            {{ __('Search') }}
        </x-jet-button>
    </div>

    {{-- The user table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm-px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium 
                                    text-gray-500 uppercase tracking-wider">
                                    {{ __('Name') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium 
                                    text-gray-500 uppercase tracking-wider">
                                    {{ __('Email') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium 
                                    text-gray-500 uppercase tracking-wider">
                                    {{ __('Connected Member') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium 
                                    text-gray-500 uppercase tracking-wider">
                                    {{ __('Role') }}
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium 
                                    text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>                              
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                                @if($data->count())
                                    @foreach($data as $item)
                                        <tr>
                                            <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $item->firstname }} {{ $item->lastname }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $item->email }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $item->memberName }}</td>
                                            <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $item->role->name }}</td>
                                            @can('update',App\Models\User::class)
                                                <td><x-jet-update-button wire:click="updateShowModal({{ $item->id }})">{{ __('Update') }}</x-jet-button></td>                                
                                            @endcan
                                            @can('delete',App\Models\User::class)
                                                <td><x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})">{{ __('Delete') }}</x-jet-button></td>                                
                                            @endcan
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="px-6 py-4 text-sm withespace-no-wrap" colspan="4">{{ __('No results found') }}</td>
                                    </tr>
                                @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br/>
    {{ $data->links() }}

    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">

        <x-slot name="title">
            {{ __('Update Role - Connected Member') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-2">
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="userName" value="{{ __('User Name') }}" />
                    <p>{{ $userFirstName }} {{ $userLastName }}</p>
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="userEmail" value="{{ __('User Email') }}" />
                    <p>{{ $userEmail }}</p>
                </div>
                <div class="mt-4 mx-4 justify-items-center">        
                    <x-jet-label for="connectedMember" value="{{ __('Connected Member is') }}" />
                    <select wire:model="connectedMember" class="appearance-none text-xs">
                        <option value="">{{ __('None') }}</option>
                        @foreach($allMembers as $member)
                            <option value="{{ $member->id }}">
                                {{ $member->firstname }} {{ $member->lastname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4 mx-4 justify-items-center">        
                    <x-jet-label for="roleName" value="{{ __('Role is') }}" />
                    <select wire:model="roleName" class="appearance-none text-xs">
                        @foreach($allRoles as $role)
                            @if($connectedMember != "" || $connectedMember != null)
                                @if($role->name != 'User')
                                    <option value="{{ $role->name }}">
                                        {{ $role->name }}
                                    </option>
                                @endif
                            @else
                                @if($role->name == 'User')
                                    <option value="{{ $role->name }}" {{ $role->name == $roleName ? "selected" : "" }}>
                                        {{ $role->name }}
                                    </option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>            
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
            @if($modelId)
                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update Role - Connected Member') }}
                </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Modal -->

    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">

        <x-slot name="title">
            {{ __('Delete User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this user?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete User') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    @if(Session::has('userError'))
        <script>
            toastr.error("{!! Session::get('userError') !!}");
        </script>
    @endif
</div>

