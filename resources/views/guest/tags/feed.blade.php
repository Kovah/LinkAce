<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $meta['title'] }}</title>
    <link href="{{ $meta['link'] }}"/>
    <updated>{{ $meta['updated'] }}</updated>
    <id>{{ $meta['id'] }}</id>
    @foreach($tags as $tag)
        <entry>
            <title><![CDATA[{{ $tag->name }}]]></title>
            <id>{{ route('tags.show', ['tag' => $tag]) }}</id>
            <author>
                <name> <![CDATA[{{ $tag->user->name }}]]></name>
            </author>
            <updated>{{ $tag->updated_at->toRfc3339String() }}</updated>
        </entry>
    @endforeach
</feed>
