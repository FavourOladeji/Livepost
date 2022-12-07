@component('mail::message')
#Welcome {{$name}} !!
@endcomponent()
@component('mail::button', ['url' => 'google.com'])
Button Text
@endcomponent()
