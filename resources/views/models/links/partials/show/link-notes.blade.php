<div class="link-notes mt-5">

    <h3 class="h6 mb-2">@lang('note.notes')</h3>

    @foreach($link->notes as $note)
        @include('models.notes.partials.single', ['note' =>$note])
    @endforeach

    @include('models.notes.partials.create', ['link' => $link])

</div>
