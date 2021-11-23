<x-user-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                            @php
                                if(Auth::user()->isAdmin) {
                                    $route = "admin.dashboard";
                                    $homeText = "Dashboard";
                                } else {
                                    $member = App\Models\Member::where('user_id',"=",auth()->user()->id)->first();
                                    if(!is_null($member)) {
                                        $route = "member.dashboard";
                                        $homeText = "Dashboard";
                                    } else {
                                       $route = "user.home";
                                       $homeText = "Home"; 
                                    }
                                }
                            @endphp
                            <a href="{{ route($route) }}" class="text-sm text-gray-700 underline">{{ __($homeText) }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">{{ __('Log in') }}</a>
    
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                @endif
    
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                        <div class="flex-shrink-0 flex items-center">
                            <!-- Logo -->
                            <a href="{{ route('user.home') }}">
                                <img src="{{ asset('images/KTTC Nijlen-Bevel.png') }}" alt="club_logo" width="150px" class="rounded-circle">
                            </a>
                        </div>
                    </div>
    
                    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('register') }}" class="underline text-gray-900 dark:text-white">Under Construction</a></div>
                                </div>
    
                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                        <p>
                                            {{ Auth::user()->firstname }}, de officiële website van KTTC Nijlen-Bevel vind je terug onder <a href="https://kttcnijlen-bevel.org/" class="underline text-primary">KTTC Nijlen-Bevel</a>. Deze website is enkel bedoeld voor testen van leden van onze club !
                                        </p><br>
                                        <p>
                                            Als je lid bent van KTTC Nijlen-Bevel en je hebt zojuist geregistreerd, dan moet een 'beheerder' je nog toegang geven als lid. Je kan hiervoor altijd iemand van het bestuur aanspreken ! 
                                        </p>
                                    </div>
                                </div>
                            </div>
    
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('register') }}" class="underline text-gray-900 dark:text-white">Bestuur ter info</a></div>
                                </div>
    
                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                        @php
                                            $boardMembers = App\Models\BoardMember::all();
                                        @endphp
                                         <p>
                                            @foreach($boardMembers as $member)
                                                <b>{{ $member->name }}</b> ({{ $member->title }}), {{ $member->address }}, {{ $member->postcode }}    {{ $member->city }}<br>
                                            @endforeach
                                        </p> 
                                    </div>
                                </div>
                            </div>
    
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ route('register') }}" class="underline text-gray-900 dark:text-white">Functionaliteiten</a></div>
                                </div>
    
                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                        Als je straks toegang krijgt tot info als lid, dan kan je op dit moment de wedstrijd raadplegen voor de ganse club. Dit is voorlopig de enige functionaliteit die beschikbaar is.
                                    </div>
                                </div>
                            </div>
    
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
                                <div class="flex items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">De Toekomst</div>
                                </div>
    
                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                        Wat gaat er nog komen ? Allereerst gaan we voorzien dat er club-berichten kunnen verschijnen waarop je ook nog commentaar kan leveren....Laat die wedstrijdverslagen maar komen. Eveneens denken wij aan een voorspeller om je toekomstig klassement uit te rekenen. En nog veel meer...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="flex justify-end mt-4 sm:items-center">
                        <div class="ml-4 text-justify  text-sm text-gray-500 sm:text-right sm:ml-0">
                            &copy;  2021 Mark Schrauwen
                        </div>
                    </div>
                </div>
            </div>
</x-user-app-layout>
