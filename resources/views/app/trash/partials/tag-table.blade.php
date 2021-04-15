<div class="table-responsive">
    <table class="table table-sm mb-0">
        <thead>
        <tr>
            <th>@lang('tag.name')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($tags as $tag)
            <tr>
                <td>
                    {{ $tag->name }}
                </td>
                <td class="text-muted">
                    <small>{{ formatDateTime($tag->created_at) }}</small>
                </td>
                <td class="text-right">
                    <form action="{{ route('trash-restore') }}" method="post">
                        @csrf
                        <input type="hidden" name="model" value="tag">
                        <input type="hidden" name="id" value="{{ $tag->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary" title="@lang('trash.restore')">
                            <x-icon.reply/>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
