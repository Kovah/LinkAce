<tbody>
<tr>
    <td colspan="2" width="85%">
        <b>
            <a href="{{ route('links.show', [$link->id]) }}">
                {{ $link->title }}
            </a>
        </b>
        <br>
        <small>
            <a href="{{ $link->url }}" target="_blank" title="@lang('link.external_link')">
                {{ $link->url }}
            </a>
        </small>
    </td>
    <td class="has-text-grey-light">
        <small title="">
            <time-ago class="has-cursor-help" datetime="{{ $link->created_at->toIso8601String() }}"
                title="{{ $link->created_at->format('Y-m-d H:i') }}">
                {{ $link->created_at->diffForHumans() }}
            </time-ago>
        </small>
    </td>
</tr>
<tr>
    <td width="30%">
        <small>
            @if($link->category)
                <label>@lang('category.category'):</label>
                <a href="{{ route('categories.show', [$link->category->id]) }}">
                    {{ $link->category->name }}
                </a>
            @else
                @lang('category.no_category')
            @endif
        </small>
    </td>
    <td>
        <small>
            @if($link->tags->count() > 0)
                <label>@lang('tag.tags'):</label>
                @foreach($link->tags as $tag)
                    <a href="{{ route('tags.show', [$tag->id]) }}" class="tag is-light">
                        {{ $tag->name }}
                    </a>
                @endforeach
            @else
                @lang('tag.no_tags')
            @endif
        </small>
    </td>
    <td>
        <div class="field has-addons is-centered">
            <div class="control">
                <a href="{{ route('links.edit', [$link->id]) }}" class="button is-small">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
            <div class="control">
                <a onclick="event.preventDefault();document.getElementById('link-delete-{{ $link->id }}').submit();"
                    title=" @lang('link.delete')" class="button is-small is-danger is-outlined">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <form id="link-delete-{{ $link->id }}" method="POST" style="display: none;"
            action="{{ route('links.destroy', [$link->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="link_id" value="{{ $link->id }}">
        </form>
    </td>
</tr>
</tbody>
