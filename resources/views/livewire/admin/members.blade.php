<div class="p-6">
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>

    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($data->count())
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="px-6 py-2">{{ $item->name }}</td>
                                        <td class="px-6 py-2">{{ $item->email }}</td>
                                        <td class="px-6 py-2">{{ $item->phone }}</td>
                                        <td class="px-6 py-2">{{ $item->mobile }}</td>
                                        <td class="px-6 py-2 flex justify-end">
                                            <x-jet-update-button wire:click="updateShowModal({{ $item->id }})">
                                                {{ __('Update') }}
                                            </x-jet-button>
                                            <x-jet-danger-button class="ml-2" wire:click="deleteShowModal({{ $item->id }})">
                                                {{ __('Delete') }}
                                            </x-jet-button>
                                        </td>
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

    <div class="mt-5">
        {{ $data->links() }}
    </div>

    {{-- Modal Form --}}

    <x-jet-dialog-modal wire:model="modalFormVisible">

        <x-slot name="title">
            {{ __('Create or Update Members') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('name') }}" />
                <x-jet-input wire:model="name" id="name" class="block mt-1 w-full" type="text" />
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('email') }}" />
                <x-jet-input wire:model="email" id="email" class="block mt-1 w-full" type="text" />
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="address" value="{{ __('address') }}" />
                <x-jet-input wire:model="address" id="address" class="block mt-1 w-full" type="text" />
            </div>
            <div class="mt-4">
                <x-jet-label for="postal_code" value="{{ __('postal_code') }}" />
                <x-jet-input wire:model="postal_code" id="postal_code" class="block mt-1 w-full" type="text" />
            </div>
            <div class="mt-4">
                <x-jet-label for="city" value="{{ __('city') }}" />
                <x-jet-input wire:model="city" id="city" class="block mt-1 w-full" type="text" />
            </div>
            <div class="mt-4">
                <x-jet-label for="phone" value="{{ __('phone') }}" />
                <x-jet-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" />
            </div>
            <div class="mt-4">
                <x-jet-label for="mobile" value="{{ __('mobile') }}" />
                <x-jet-input wire:model="mobile" id="mobile" class="block mt-1 w-full" type="text" />
            </div>
            <div class="mt-4">
                <x-jet-label for="birthdate" value="{{ __('birthdate (yyyy-mm-dd)') }}" />
                <x-jet-input wire:model="birthdate" id="birthdate" class="block mt-1 w-full" type="text" />
                @error('birthdate') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="user_id" value="{{ __('User') }}" />
                <select wire:model="user_id" id="user_id" class="block appearance-none w-full 
                bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight 
                focus:outline-none focus:bg-white focus:border-gray-500">
                <option value="">-- Select a User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->firstname . ' ' . $user->lastname }}</option>
                @endforeach
                </select>
            </div>          
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
            @if($modelId)
                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update Member') }}
                </x-jet-button>
            @else
                <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Create Member') }}
                </x-jet-button>
            @endif

        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Modal -->
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">

        <x-slot name="title">
            {{ __('Delete Navigation Item') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this navigation item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete Navigation Item') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

</div>