<tr>
    <td>
        @if(isset($is_child))
            &rightarrow;
        @endif
        <a href="{{ route('categories.show', [$category->id]) }}">
            {{ $category->name }}
        </a>
    </td>
    <td>
        <small>{{ $category->description }}</small>
    </td>
    <td class="text-right">
        <small>{{ $category->links->count() }}</small>
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('categories.edit', [$category->id]) }}" class="btn  btn-outline-primary">
                <i class="fa fa-pencil"></i>
            </a>
            <a onclick="event.preventDefault();document.getElementById('category-delete-{{ $category->id }}').submit();"
                title=" @lang('category.delete')" class="btn btn-outline-danger">
                <i class="fa fa-trash"></i>
            </a>
        </div>
        <form id="category-delete-{{ $category->id }}" method="POST" style="display: none;"
            action="{{ route('categories.destroy', [$category->id]) }}">
            @method('DELETE')
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">
        </form>
    </td>
</tr>
