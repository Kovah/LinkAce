<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $meta['title'] }}</title>
    <link href="{{ $meta['link'] }}"/>
    <updated>{{ $meta['updated'] }}</updated>
    <id>{{ $meta['id'] }}</id>
    @foreach($lists as $list)
        <entry>
            <id>{{ route('lists.show', ['list' => $list]) }}</id>
            <title><![CDATA[{{ $list->name }}]]></title>
            <link rel="alternate" href="{{ route('lists.show', ['list' => $list]) }}"/>
            <author>
                <name> <![CDATA[{{ $list->user->name }}]]></name>
            </author>
            <summary type="html">
                <![CDATA[{!! $list->description !!}]]>
            </summary>
            <updated>{{ $list->updated_at->toRfc3339String() }}</updated>
        </entry>
    @endforeach
</feed>
