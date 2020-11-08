<div class="table-responsive">
    <table class="table table-sm mb-0">
        <thead>
        <tr>
            <th>@lang('link.url')</th>
            <th>@lang('linkace.added_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($links as $link)
            <tr>
                <td>
                    {{ $link->url }}
                </td>
                <td class="text-muted">
                    <small>{{ formatDateTime($link->created_at) }}</small>
                </td>
                <td class="text-right">
                    <form action="{{ route('trash-restore') }}" method="post">
                        @csrf
                        <input type="hidden" name="model" value="link">
                        <input type="hidden" name="id" value="{{ $link->id }}">
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
