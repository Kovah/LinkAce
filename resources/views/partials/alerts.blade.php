@include('flash::message')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 list-unstyled">
            @foreach (array_unique($errors->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
