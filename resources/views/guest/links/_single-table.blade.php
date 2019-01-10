<tr>
    <td>
        {{ $link->title }}
    </td>
    <td>
        <a href="{{ $link->url }}" target="_blank">
            {{ $link->url }}
        </a>
    </td>
    <td class="text-muted">
        <small>
            {!! $link->addedAt() !!}
        </small>
    </td>
    <td>
        {{ $link->user->name }}
    </td>
</tr>
