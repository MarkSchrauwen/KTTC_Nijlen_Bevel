<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div>
        <img src="{{ asset('images/KTTC Nijlen-Bevel.png') }}" alt="club_logo" width="100px" class="rounded-circle">
    </div>

    <div class="mt-8 text-2xl">
        Welkom {{ $this->firstname }} in het dashboard voor het beheer van KTTC Nijlen-Bevel !
    </div>

    <div class="mt-6 text-gray-500">
        Hieronder vind je de meest recente info en gebeurtenissen die je aandacht vereisen. Evenals alle hulpmiddelen om de website voor de club te beheren !
    </div>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
    <div class="p-6">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <div class="relative ml-4 text-lg text-gray-600 leading-7 font-semibold">
                @if($numberAdminNotifications != null)
                    @php
                        $array_notifications = [];
                        foreach($adminNotifications as $item) {
                            array_push($array_notifications,$item->id);
                        }
                    @endphp
                    <a href="{{ route('admin.notifications', json_encode($array_notifications)) }}">
                        {{ __('Notifications') }}
                        <span class="absolute ml-1 -top-2 -right-5 px-1 py-1 text-xs bg-red-500 text-red-50 rounded">
                            {{ $numberAdminNotifications }}
                        </span>                                         
                    </a>
                @else
                    <a href="{{ route('admin.notifications') }}">
                        {{ __('Notifications') }}                                         
                    </a>
                @endif
            </div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500">
                {{ __('If indicated there are Notifications to be administered throught the link.') }}
            </div>
            <a href="{{ route('admin.notifications') }}">
                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                        <div>{{ __('Notification Management') }}</div>
                        <div class="ml-1 text-indigo-500">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                </div>
            </a>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <div class="relative ml-4 text-lg text-gray-600 leading-7 font-semibold">
                @if($numberCompetitionNotifications != null)
                @php
                    $array_notifications = [];
                    foreach($competitionNotifications as $item) {
                        array_push($array_notifications,$item->id);
                    }
                @endphp
                    <a href="{{ route('admin.notifications', ["array_notifications" => json_encode($array_notifications)]) }}">
                        {{ __('Competitions') }}
                        <span class="absolute ml-1 -top-2 -right-5 px-1 py-1 text-xs bg-red-500 text-red-50 rounded">
                            {{ $numberCompetitionNotifications }}
                        </span>                                         
                    </a>
                @else
                    <a href="{{ route('admin.notifications') }}">
                        {{ __('Competitions') }}                                         
                    </a>
                @endif
            </div>
        </div>

        <div class="ml-12">
            <div class="flex flex-row flex-1 mt-2 text-sm text-gray-500">
                <div class="mr-2">                
                    <u>{{ __('Next Competitions') }}</u> :
                    <br/> 
                    @forelse($next10Competitions as $competition)
                        <div class="text-xs">
                            {{ $competition->competition_date->format('D d-m-y') }}   {{ $competition->home_team }} - {{ $competition->visitor_team }}   
                            @foreach($all_members as $singleMember)
                                @if(in_array($singleMember->id, $competition->participants))
                                    <span class="badge bg-gray-400 text-xs">{{ $singleMember->firstname }} {{ $singleMember->lastname }} </span>
                                @endif
                            @endforeach
                        </div>                
                    @empty
                        {{ __('No Competitions') }}
                    @endforelse
                </div>
            </div>
            <br/>
            <div class="text-sm">
                {{ __('For more details')  }} ...
                    <a href="{{ route('member.competitions') }}" class="mt-3 flex items-center font-semibold text-indigo-700">
                        {{ __('Consult Competitions') }}
                        <div class="ml-1 text-indigo-700">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </a>            
            </div>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="#">Instellingen voor de club</a></div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500">
                Beheer alle instellingen voor de club met betrekking tot de website.
            </div>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200 md:border-l">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Beheer van mail en berichten</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500">
                Hieronder vatten wij alle communicatie met de leden samen !
            </div>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200 md:border-l">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Beheer van de leden</div>
        </div>

        <div class="ml-12">
            <div class="mt-2 text-sm text-gray-500">
                Hier kan je al het beheer voor de leden doen. Gaande van inschrijvingen, persoonlijke mails enz... !
            </div>
        </div>
    </div>
</div>
