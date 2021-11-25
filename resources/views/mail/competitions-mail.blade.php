@component('mail::message')
<u>{{ __('Reminder for competitions') }}</u>

<p>
    {{ __('Dear') }} {{ $member->firstname }},
</p>
<p>
    {{ __('Hereunder you will find the competitions you will participate in for the coming days') }}:
</p>
@foreach ($competitions as $competition)
    <div>
        {{ date('D d-m-y',strtotime($competition['competition_date'])) }} {{ $competition['competition_time'] }} -> {{ $competition['home_team'] }} - {{ $competition['visitor_team'] }}       
    </div>
    <div>
        <small>{{ __('Comment') }} : {{ $competition['comment'] }}</small>
    </div>
    <div>
        <small>
            {{ __('Participants') }} :
            @foreach($competition['members'] as $member)
            <span> {{ $member['firstname'] }} {{ $member['lastname'] }}</span>
            @endforeach
        </small>
    </div>
@endforeach

@component('mail::button', ['url' => env('APP_URL')])
{{ __('Visit Website') }}
@endcomponent

{{ __('Thanks and good luck') }},<br>
{{ config('app.name') }}
@endcomponent
