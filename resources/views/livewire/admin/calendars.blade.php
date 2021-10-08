<div>
    <div>
        <h1>Competitions</h1>
    </div>
    <br>
    <hr>
    <select class="form-control" wire:model="selectedCompetitionOrganisation">
        <option value="">{{ __('-- Select Organisation') }}</option>
        @foreach($competition_organisations as $organisation)
            <option value="{{ $organisation->name }}">{{ $organisation->name }}</option>
        @endforeach
    </select>
    <label for="">{{ __('Enter season eg.(2019-2020)') }}</label>
    <input type="text" wire:model="selectedSeason">
    <select class="form-control" wire:model="selectedLevel">
        <option value="">{{ __('-- Select Level --') }}</option>
        @if($levels != null)
            @foreach($levels as $level)
                <option value="{{ $level['value'] }}">{{ $level['text'] }}</option>
            @endforeach
        @endif     
    </select>

    <select class="form-control" wire:model="selectedClub">
        <option value="">{{ __('-- Select Club --') }}</option>
        @if($clubs != null)
            @foreach($clubs as $club)
                <option value="{{ $club['value'] }}">{{ $club['text'] }}</option>
            @endforeach
        @endif      
    </select>
    <br>

    <select class="form-control" wire:model="selectedDivision">
        <option value="">{{ __('-- Select Division --') }}</option>
        @if($divisions != null)
            @foreach($divisions as $division)
                <option value="{{ $division['value'] }}">{{ $division['text'] }}</option>
            @endforeach
        @endif      
    </select>
    <br>

    <label for="club">Enter exact Club Name (eg. Nijlen A)</label>
    <select wire:model="selectedTeam">
        <option value="">{{ __('-- Select the corresponding team') }}</option>
        @foreach ($competition_teams as $team)
            <option value="{{ $team->name }}">{{ $team->name }}</option>
        @endforeach
    </select>

    <div>
        @if($competitions != null)
            <table>
                <thead>
                    <tr>
                        <td>{{ __('Competition') }}</td>
                        <td>{{ __('Season') }}</td>
                        <td>{{ __('Competition_number') }}</td>
                        <td>{{ __('Competition_date') }}</td>
                        <td>{{ __('Competition_time') }}</td>
                        <td>{{ __('Home') }}</td>
                        <td>{{ __('Visitor') }}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($competitions as $competition)
                        <tr>
                            <td>{{ $selectedCompetitionOrganisation }}</td>
                            <td>{{ $selectedSeason }}</td>
                            <td>{{ $competition[0] }}</td>
                            <td>{{ $competition[1] }}</td>
                            <td>{{ $competition[4] }}</td>
                            <td>{{ $competition[2] }}</td>
                            <td>{{ $competition[3] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No results</p>           
        @endif

        <br>
        <x-jet-success-button type="submit" wire:click.prevent="saveCompetition">Save Competition</x-jet-success-button>        
    </div>
</div>
