<div class="link-notes mt-5">

    <h3 class="h6 mb-2">@lang('note.notes')</h3>

    @if($link->notes->count())
        @foreach($link->notes as $note)
            @include('models.notes.partials.single', ['note' =>$note])
        @endforeach
    @endif

    @include('models.notes.partials.create', ['link' => $link])

</div>
