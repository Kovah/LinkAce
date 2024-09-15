<div class="link-taxonomy row mt-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                @lang('list.lists')
            </div>
            <div class="card-body py-2">
                @if(!$link->lists->isEmpty())
                    @foreach($link->lists->filter() as $list)
                        @if(systemsettings('guest_access_enabled'))
                            <a href="{{ route('guest.lists.show', ['list' => $list]) }}" class="btn btn-sm btn-light m-1">
                                <x-models.name-with-user :model="$list"/>
                            </a>
                        @else
                            <x-models.name-with-user :model="$list" class="m-1"/>
                        @endif
                    @endforeach
                @else
                    <div class="text-pale small">@lang('list.no_lists')</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                @lang('tag.tags')
            </div>
            <div class="card-body py-2">
                @if(!$link->tags->isEmpty())
                    @foreach($link->tags as $tag)
                        @if(systemsettings('guest_access_enabled'))
                            <a href="{{ route('guest.tags.show', ['tag' => $tag]) }}" class="btn btn-sm btn-light m-1">
                                <x-models.name-with-user :model="$tag"/>
                            </a>
                        @else
                            <x-models.name-with-user :model="$tag" class="m-1"/>
                        @endif
                    @endforeach
                @else
                    <div class="text-pale small">@lang('tag.no_tags')</div>
                @endif
            </div>
        </div>
    </div>
</div>
