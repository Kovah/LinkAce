<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $meta['title'] }}</title>
    <link rel="self" type="application/atom+xml" href="{{ $meta['link'] }}"/>
    <updated>{{ $meta['updated'] }}</updated>
    <id>{{ $meta['id'] }}</id>
    @foreach($links as $link)
        <entry>
            <id>{{ route('links.show', ['link'=> $link]) }}</id>
            <title type="text"><![CDATA[{{ strip_tags($link->title) }}]]></title>
            <link rel="alternate" href="{{ $link->url }}" />
            <link rel="via" type="application/atom+xml" href="{{ route('links.show', ['link'=> $link]) }}"/>
            <author>
                <name> <![CDATA[{{ $link->user->name }}]]></name>
            </author>
            <summary type="text">
                <![CDATA[{!! strip_tags($link->description) !!}]]>
            </summary>
            <updated>{{ $link->updated_at->toRfc3339String() }}</updated>
        </entry>
    @endforeach
</feed>
