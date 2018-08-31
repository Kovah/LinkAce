@if (session()->has('alert.message'))
    <div class="notification is-{{ session()->get('alert.style') }}" role="alert">
        {!! session()->get('alert.message') !!}
    </div><!-- /alert -->
@endif
