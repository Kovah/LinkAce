<table class="table">
    <thead>
    <tr>
        <th>@lang('tag.name')</th>
        <th>@lang('link.links')</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
        <tr>
            <td>
                <a href="{{ route('tags.show', [$tag->id]) }}">
                    {{ $tag->name }}
                </a>
            </td>
            <td>
                {{ $tag->links->count() }}
            </td>
            <td>
                <div class="field has-addons">
                    <div class="control">
                        <a href="{{ route('tags.edit', [$tag->id]) }}" class="button is-small">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <div class="control">
                        <a onclick="event.preventDefault();document.getElementById('tag-delete-{{ $tag->id }}').submit();"
                            title=" @lang('tag.delete')" class="button is-small is-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
                <form id="tag-delete-{{ $tag->id }}" method="POST" style="display: none;"
                    action="{{ route('tags.destroy', [$tag->id]) }}">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="tag_id" value="{{ $tag->id }}">
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
