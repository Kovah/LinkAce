<div class="bulk-edit table-responsive" data-type="links">
    <table class="table mb-0">
        <thead>
        <tr>
            <th>@lang('link.title')</th>
            <th>@lang('link.url')</th>
            <th style="min-width:90px;">@lang('linkace.added_at')</th>
            <th>
                <form class="bulk-edit-form visually-hidden text-end" action="{{ route('bulk-edit.form') }}" method="POST">
                    @csrf()
                    <input type="hidden" name="type">
                    <input type="hidden" name="models">
                    <div class="btn-group mt-1">
                        <button type="button" class="bulk-edit-submit btn btn-outline-primary btn-xs">Edit</button>
                        <button type="button" class="bulk-edit-select-all btn btn-outline-primary btn-xs">Select all</button>
                    </div>
                </form>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $link)
            <tr>
                <td>
                    <a href="{{ route('links.show', [$link->id]) }}">
                        {{ $link->title }}
                    </a>
                    @if($link->tags->count() > 0)
                        <div class="mt-1">
                            @foreach($link->tags as $tag)
                                <a href="{{ route('tags.show', ['tag' => $tag]) }}" class="btn btn-xs btn-light">
                                    <x-models.name-with-user :model="$tag"/>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="text-condensed">
                    <a href="{{ $link->url }}" {!! linkTarget() !!} class="small short-text">
                        {{ $link->shortUrl() }}
                    </a>
                </td>
                <td class="text-pale small text-condensed">{!! $link->addedAt() !!}</td>
                <td class="text-end">
                    <input type="checkbox" aria-label="@lang('')" class="bulk-edit-model form-check d-inline-block"
                        data-id="{{ $link->id }}">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
