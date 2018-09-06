@if (session()->has('alert.message'))
    <div class="notification is-{{ session()->get('alert.style') }}" role="alert">
        {!! session()->get('alert.message') !!}
    </div><!-- /alert -->
@endif

@if ($errors->any())
    <div class="notification is-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
