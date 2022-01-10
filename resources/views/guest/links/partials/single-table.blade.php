<tr>
    <td>
        {{ $link->title }}
    </td>
    <td>
        <a href="{{ $link->url }}" {!! linkTarget() !!} class="text-decoration-none">
            {{ $link->url }}
        </a>
    </td>
    <td class="text-muted">
        <small>
            {!! $link->addedAt() !!}
        </small>
    </td>
</tr>
