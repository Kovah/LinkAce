<tr>
    <td>
        @if(isset($is_child))
            {!! '<small>' !!}
            &rightarrow;
        @endif
        <a href="{{ route('categories.show', [$category->id]) }}">
            {{ $category->name }}
        </a>
        @if(isset($is_child)) {!! '</small>' !!} @endif
    </td>
    <td>
        <small>{{ $category->description }}</small>
    </td>
    <td class="has-text-right">
        <small>{{ $category->links->count() }}</small>
    </td>
    <td>
        <div class="field has-addons">
            <div class="control">
                <a href="{{ route('categories.edit', [$category->id]) }}" class="button is-small">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
            <div class="control">
                <a onclick="event.preventDefault();document.getElementById('category-delete-{{ $category->id }}').submit();"
                    title=" @lang('category.delete')" class="button is-small is-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <form id="category-delete-{{ $category->id }}" method="POST" style="display: none;"
            action="{{ route('categories.destroy', [$category->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">
        </form>
    </td>
</tr>
