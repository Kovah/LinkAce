<tr>
    <td>
        {{ $link->shortTitle() }}
    </td>
    <td>
        <a href="{{ $link->url }}" {!! linkTarget() !!}>
            {{ $link->url }}
        </a>
    </td>
    <td class="text-pale">
        <small>
            {!! $link->addedAt() !!}
        </small>
    </td>
</tr>
