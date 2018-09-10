@if (session()->has('alert.message'))
    <div class="alert alert-{{ session()->get('alert.style') }}" role="alert">
        {!! session()->get('alert.message') !!}
    </div><!-- /alert -->
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
