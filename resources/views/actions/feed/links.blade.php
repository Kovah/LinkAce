<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $meta['title'] }}</title>
    <link href="{{ $meta['link'] }}"/>
    <updated>{{ $meta['updated'] }}</updated>
    <id>{{ $meta['id'] }}</id>
    @foreach($links as $link)
        <entry>
            <id>{{ $link->url }}</id>
            <title><![CDATA[{{ $link->title }}]]></title>
            <link rel="alternate" href="{{ route('links.show', ['link'=> $link]) }}" />
            <author>
                <name> <![CDATA[{{ $link->user->name }}]]></name>
            </author>
            <summary type="html">
                <![CDATA[{!! $link->markdownDescription !!}]]>
            </summary>
            <updated>{{ $link->updated_at->toRfc3339String() }}</updated>
        </entry>
    @endforeach
</feed>
