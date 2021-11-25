<x-admin-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="card">
                    <div class="card-header">
                        {{ __('Notifications') }}
                    </div>
                    <div class="card-body">
                        @forelse($notifications as $item)
                            {{-- Switch for all the different notification cases --}}

                            @switch($item->type)
                                @case('App\Notifications\NewUserNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('User') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Registered with email') }} 
                                        <span class="text-indigo-700 font-bold">{{  $item->data['email']  }}</span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\CreateMemberNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Created a new Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['member']  }}</span>
                                        @if(!empty($item->data['connectedUser']))
                                            {{ __('linked to User') }} 
                                            <span class="text-indigo-700 font-bold">{{ $item->data['connectedUser'] }}</span>
                                        @endif
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\UpdateMemberNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Updated Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['member']  }}</span>
                                        @if(!empty($item->data['connectedUser']))
                                            {{ __('linked to User') }} 
                                            <span class="text-indigo-700 font-bold">{{ $item->data['connectedUser'] }}</span>
                                        @endif
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\DeleteMemberNotification')
                                    <div class="alert alert-danger text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-red-600 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Deleted Member') }} 
                                        <span class="text-red-600 font-bold">{{ $item->data['member']  }}</span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\UpdateUserNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Updated User') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['concernedUser']  }}</span>
                                        @if(!empty($item->data['connectedMember']))
                                            {{ __('linked to Member') }} 
                                            <span class="text-indigo-700 font-bold">{{ $item->data['connectedMember'] }}</span>
                                        @endif
                                         {{ __('with Role') }}
                                        <span class="text-indigo-700 font-bold">{{ $item->data['role'] }}</span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\DeleteUserNotification')
                                    <div class="alert alert-danger text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-red-600 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Deleted User') }} 
                                        <span class="text-red-600 font-bold">{{ $item->data['concernedUser']  }}</span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\CreateCompetitionNotification')
                                @php
                                    $competition = App\Models\Competition::find($item->data['competitionId']);
                                @endphp
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Created Competition') }} 
                                        <span class="text-indigo-700 font-bold">
                                            {{ $competition->competition_date->format('D d-m-y') }} {{ $competition->home_team }} - {{ $competition->visitor_team }}
                                        </span>
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                @break
                                @case('App\Notifications\UpdateCompetitionNotification')
                                    @php
                                        $competition = App\Models\Competition::find($item->data['competitionId']);
                                    @endphp
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Updated Competition') }} 
                                        <span class="text-indigo-700 font-bold">
                                            {{ $competition->competition_date->format('D d-m-y') }} {{ $competition->home_team }} - {{ $competition->visitor_team }}
                                        </span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                @break
                                @case('App\Notifications\DeleteCompetitionNotification')
                                    @php
                                        $competition = App\Models\Competition::find($item->data['competitionId']);
                                    @endphp
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $item->id }}">
                                        {{ __('Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $item->data['admin'] }}</span>
                                        {{ __('Deleted Competition') }} 
                                        <span class="text-indigo-700 font-bold">
                                            {{ $competition->competition_date->format('D d-m-y') }} {{ $competition->home_team }} - {{ $competition->visitor_team }}
                                        </span>
                                        *** {{ $item->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                @break
                            @endswitch

                            @if($loop->last)
                                <a href="#" class="text-blue-500" id="mark-all" data-notifications="{{ $notifications }}">
                                    {{ __('Mark all as read') }}
                                </a>
                            @endif
                        @empty
                            {{ __('No Notifications found !') }}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-app-layout>

@if(auth()->user()->isAdmin)
    <script>
    function sendMarkRequest(id = null, notifications = null) {
        return $.ajax("{{ route('admin.markNotification') }}", {
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "notifications": notifications,
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id') );
            request.done(() => {
                $(this).remove();
            });
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest(null,$(this).data('notifications'));
            request.done(() => {
                $('div.alert').remove();
            })
        });
    });
    </script>
@endif