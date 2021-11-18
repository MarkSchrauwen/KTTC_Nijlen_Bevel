<div class="p-6">
    <div class="flex flex-row items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-success-button wire:click="searchModal">
            {{ __('Search') }}
        </x-jet-button>
    </div>

    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead>
                            <tr>
                                <th class="w-2/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                <th class="w-1/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Time') }}</th>
                                <th class="w-1/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Home Team') }}</th>
                                <th class="w-1/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Visitor Team') }}</th>
                                <th class="w-2/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Participants') }}</th>
                                <th class="w-3/12 px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Comment') }}</th>
                                <th class="px-2 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($data->count())
                                @foreach ($data as $item)
                                    <tr>
                                        @php
                                            if($item->competition_date != null) {
                                                $formattedDate = $item->competition_date->format('D d-m-y');
                                            } else {
                                                $formattedDate = $item->competition_date;
                                            }
                                            if($item->comment != null) {
                                                $shortComment = substr($item->comment,0,80) . "...";
                                            } else {
                                                $shortComment = "";
                                            }
                                        @endphp
                                        <td class="w-2/12 px-2 py-2 text-xs">{{ $formattedDate }}</td>
                                        <td class="w-1/12 px-2 py-2 text-xs">{{ $item->competition_time}}</td>
                                        <td class="w-1/12 px-2 py-2 text-xs">{{ $item->home_team }}</td>
                                        <td class="w-1/12 px-2 py-2 text-xs">{{ $item->visitor_team }}</td>
                                        <td class="w-2/12 px-2 py-2 text-xs">
                                            @foreach ($item->members as $singleMember)
                                                <span class="badge bg-primary text-xs">{{ $singleMember->firstname }} {{ $singleMember->lastname }}</span>
                                            @endforeach
                                        </td>
                                        <td class="w-3/12 px-2 py-2 text-xs">{{ $shortComment }}</td>
                                        <td class="px-2 py-2 flex justify-end">
                                            @can('update', $item)
                                                <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                                                    {{ __('Update') }}
                                                </x-jet-button>
                                            @endcan
                                            @can('view', $item)
                                                <x-jet-danger-button class="ml-2" wire:click="detailsShowModal({{ $item->id }})">
                                                    {{ __('Details') }}
                                                </x-jet-button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-6 py-4 text-sm withespace-no-wrap" colspan="4">{{ __('No Results Found') }}</td>
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

    {{-- Modal Forms --}}

    <x-jet-dialog-modal wire:model="modalFormVisible">

        <x-slot name="title">
            {{ __('Update Competition') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-2">
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition" value="{{ __('Organizer Competition') }}" />
                    <select wire:model="competition" wire:ignore class="text-xs">
                        @foreach($competition_names as $singleCompetition)
                            <option value="{{ $singleCompetition->name }}" {{ $singleCompetition->name == $competition ? "selected" : ""  }}>
                                {{ $singleCompetition->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('competition_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center">        
                    <x-jet-label for="team_name" value="{{ __('Team Name') }}" />
                    <select wire:model="team_name" wire:ignore class="text-xs">
                        @foreach($team_names as $singleTeam)
                            <h1>{{ $singleTeam->name == $team_name ? "selected" : "" }}</h1>
                            <option value="{{ $singleTeam->name }}" {{ $singleTeam->name == $team_name ? "selected" : "" }}>
                                {{ $singleTeam->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="season" value="{{ __('Season') }} (yyyy-yyyy)" />
                    <x-jet-input wire:model="season" id="season" class="block mt-1 w-full" type="text" />
                    @error('season') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition_number" value="{{ __('Competition Number') }}" />
                    <x-jet-input wire:model="competition_number" id="competition_number" class="block mt-1 w-full" type="text" />
                    @error('competition_number') <span class="error">{{ $message }}</span> @enderror
                </div> 
                 <div class="mt-4 mx-4 justify-items-center" id="datepicker" wire:ignore>
                    <x-jet-label for="competition_date" value="{{ __('Date') }}" />
                    <x-date-picker wire:model="competition_date" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    @error('competition_date') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center" id="timepicker" wire:ignore>
                    <x-jet-label for="competition_time" value="{{ __('Time') }}" />
                    <x-time-picker wire:model="competition_time" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    @error('competition_time') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="home_team" value="{{ __('Home Team') }}" />
                    <x-jet-input wire:model="home_team" id="home_team" class="block mt-1 w-full" type="text" />
                    @error('home_team') <span class="error">{{ $message }}</span> @enderror
                </div> 
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="visitor_team" value="{{ __('Visitor Team') }}" />
                    <x-jet-input wire:model="visitor_team" id="visitor_team" class="block mt-1 w-full" type="text" />
                    @error('visitor_team') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="participants" value="{{ __('Participants') }}" />
                    <select class="selectpicker" name="participants[]" id="participants" multiple wire:ignore wire:model="participants">
                        @foreach($all_members as $singleMember)
                            <option value="{{ $singleMember->id }}">
                                {{ $singleMember->firstname }} {{ $singleMember->lastname }}</option>
                        @endforeach
                    </select>
                    @error('participants') <span class="error">{{ $message }}</span> @enderror
                </div> 
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="comment" value="{{ __('Comment') }}" />
                    <textarea wire:model="comment" id="comment" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full resize-y"></textarea>
                    @error('comment') <span class="error">{{ $message }}</span> @enderror
                </div>          
            </div>            
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>
            @if($modelId)
                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update Competition') }}
                </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Details Modal -->
    <x-jet-dialog-modal wire:model="modalDetailsFormVisible">

        <x-slot name="title">
            {{ __('Competition Details') }}
        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-2 gap-2">
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition" value="{{ __('Organizer Competition') }} : " />
                    {{ $competition }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">        
                    <x-jet-label for="team_name" value="{{ __('Team Name') }}" />
                    {{ $team_name }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="season" value="{{ __('Season') }} (yyyy-yyyy)" />
                    {{ $season }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition_number" value="{{ __('Competition Number') }}" />
                    {{ $competition_number }}
                </div> 
                 <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition_date" value="{{ __('Date') }}" wire:ignore />
                    {{ $competition_date ? $competition_date : "" }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="competition_time" value="{{ __('Time') }}" />
                    {{ $competition_time }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="home_team" value="{{ __('Home Team') }}" />
                    {{ $home_team }}
                </div> 
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="visitor_team" value="{{ __('Visitor Team') }}" />
                    {{ $visitor_team }}
                </div>
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="participants" value="{{ __('Participants') }}" />
                    @foreach($all_members as $singleMember)
                        @if(in_array($singleMember->id, $participants))
                            {{ $singleMember->firstname }} {{ $singleMember->lastname }}
                        @endif
                    @endforeach
                </div> 
                <div class="mt-4 mx-4 justify-items-center">
                    <x-jet-label for="comment" value="{{ __('Comment') }}" />
                    {{ $comment }}
                    
                </div>          
            </div>            
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalDetailsFormVisible')" wire:loading.attr="disabled">
                {{ __('Done') }}
            </x-jet-secondary-button>

        </x-slot>
    </x-jet-dialog-modal>

        <!-- Search Participants Modal -->
        <x-jet-dialog-modal wire:model="modalSearchVisible">

            <x-slot name="title">
                    {{ __('Search') }}
            </x-slot>
    
            <x-slot name="content">
                <div class="m-2">
                    <label class="text-sm">{{ __('Start date') }}</label>
                    <x-date-picker wire:model="start_date" class="border-gray-300 text-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                </div>
                <div class="m-2">
                    <label class="text-sm">{{ __('End date') }}</label>
                    <x-date-picker wire:model="end_date" class="border-gray-300 text-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                </div>
                <div class="m-2">
                    <label class="text-sm">{{ __('Organisation') }}</label>
                    <select wire:model="competition_search_name" class="block w-100 text-sm appearance-none
                    bg-gray-100 border border-gray-300 text-gray-700 mx-1 py-1 px-2 pr-8 rounded round leading-tight 
                    focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">{{ __('All') }}</option>
                        @foreach($competition_names as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="m-2">
                    <label class="text-sm">{{ __('Teams') }}</label>
                    <select wire:model="team_search_name" class="block w-full text-sm appearance-none 
                    bg-gray-100 border border-gray-300 text-gray-700 mx-1 py-1 px-2 pr-8 rounded round leading-tight 
                    focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">{{ __('All') }}</option>
                        @foreach($team_names as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="m-2">
                    <label class="text-sm">{{ __('Participants') }}</label>
                    <select wire:model="participants_search" multiple size="4" class="block w-100 text-sm appearance-none
                    bg-gray-100 border border-gray-300 text-gray-700 mx-1 py-1 px-2 pr-8 rounded round leading-tight 
                    focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">{{ __('All') }}</option>
                        @foreach($all_members as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>                    
                </div>
            </x-slot>
    
            <x-slot name="footer">
                <x-jet-button wire:click="resetSearchCriteria">
                    {{ __('Reset Search') }}
                </x-jet-button>
                <x-jet-secondary-button wire:click="$toggle('modalSearchVisible')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>
    
                <x-jet-success-button class="ml-2" wire:click="searchSubmit" wire:loading.attr="disabled">
                    {{ __('Search') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
</div>
