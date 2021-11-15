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
                        @forelse($notifications as $notification)
                            {{-- Switch for all the different notification cases --}}

                            @switch($notification->type)
                                @case('App\Notifications\NewUserNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('User') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['name'] }}</span>
                                        {{ __('Registered with email') }} 
                                        <span class="text-indigo-700 font-bold">{{  $notification->data['email']  }}</span>
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\CreateMemberNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['admin'] }}</span>
                                        {{ __('Created a new Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['member']  }}</span>
                                        @if(!empty($notification->data['connectedUser']))
                                            {{ __('linked to User') }} 
                                            <span class="text-indigo-700 font-bold">{{ $notification->data['connectedUser'] }}</span>
                                        @endif
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\UpdateMemberNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['admin'] }}</span>
                                        {{ __('Updated Member') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['member']  }}</span>
                                        @if(!empty($notification->data['connectedUser']))
                                            {{ __('linked to User') }} 
                                            <span class="text-indigo-700 font-bold">{{ $notification->data['connectedUser'] }}</span>
                                        @endif
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\DeleteMemberNotification')
                                    <div class="alert alert-danger text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-red-600 font-bold">{{ $notification->data['admin'] }}</span>
                                        {{ __('Deleted Member') }} 
                                        <span class="text-red-600 font-bold">{{ $notification->data['member']  }}</span>
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\UpdateUserNotification')
                                    <div class="alert alert-success text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['admin'] }}</span>
                                        {{ __('Updated User') }} 
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['concernedUser']  }}</span>
                                        @if(!empty($notification->data['connectedMember']))
                                            {{ __('linked to Member') }} 
                                            <span class="text-indigo-700 font-bold">{{ $notification->data['connectedMember'] }}</span>
                                        @endif
                                         {{ __('with Role') }}
                                        <span class="text-indigo-700 font-bold">{{ $notification->data['role'] }}</span>
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                                @case('App\Notifications\DeleteUserNotification')
                                    <div class="alert alert-danger text-sm mark-as-read" role="alert" data-id="{{ $notification->id }}">
                                        {{ __('Administrator') }} 
                                        <span class="text-red-600 font-bold">{{ $notification->data['admin'] }}</span>
                                        {{ __('Deleted User') }} 
                                        <span class="text-red-600 font-bold">{{ $notification->data['concernedUser']  }}</span>
                                        *** {{ $notification->created_at }}
                                        <a href="#" class="text-blue-500 float-right">
                                            {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                    @break
                            @endswitch

                            @if($loop->last)
                                <a href="#" class="text-blue-500" id="mark-all">
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
    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('admin.markNotification') }}", {
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                $(this).remove();
            });
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.alert').remove();
            })
        });
    });
    </script>
@endif