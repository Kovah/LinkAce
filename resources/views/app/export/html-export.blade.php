<!DOCTYPE NETSCAPE-Bookmark-file-1>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<!-- This is an automatically generated file.
     It will be read and overwritten.
     Do Not Edit! -->
<TITLE>{{ config('app.name') }}</TITLE>
<DL>
    <p>
        @foreach($links as $link)
            <DT>
                <A HREF="{{ $link->url }}" ADD_DATE="{{ $link->created_at->timestamp }}" PRIVATE="{{ $link->is_private ? 1 : 0 }}" @if($link->tags) TAGS="{{ implode(',', $link->tags->pluck('name')->all()) }}" @endif >{{ $link->title }}</A>
            @if($link->description)
                <DD>{{ $link->description }}
            @endif
        @endforeach
</DL>
