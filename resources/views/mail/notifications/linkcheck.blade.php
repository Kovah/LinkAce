@component('mail::message')

@lang('link.notifications.linkcheck.errors')

@if(count($moved_links) > 0)
@lang('link.notifications.linkcheck.errors.moved')

@foreach($moved_links as $link)
* [{{ $link->title }}]({{ $link->url }})
@endforeach
@endif

@if(count($broken_links) > 0)
@lang('link.notifications.linkcheck.errors.broken')

@foreach($broken_links as $link)
* [{{ $link->title }}]({{ $link->url }})
@endforeach
@endif

@component('mail::button', ['url' => $linkace_url])
@lang('linkace.open_linkace')
@endcomponent

@lang('link.happy_bookmarking'),<br>
{{ config('app.name') }}
@endcomponent
