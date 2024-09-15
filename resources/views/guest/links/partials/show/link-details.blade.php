<div class="link-details">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row mb-3">
                @if($link->thumbnail)
                    <a href="{{ $link->url }}" {!! linkTarget() !!}
                    class="rounded d-block mt-lg-1 me-lg-2 align-self-center link-thumbnail link-thumbnail-detail"
                        style="background-image: url('{{ $link->thumbnail }}') ;">
                    </a>
                @endif
                <div class="d-sm-inline-block mt-1 mb-2 mb-sm-0">
                    {!! $link->getIcon('me-1 me-sm-2') !!}
                </div>
                <h3 class="title d-inline-block mb-0">
                    <a href="{{ $link->url }}" {!! linkTarget() !!}>{{ $link->title }}</a>
                </h3>
            </div>

            <div class="text-pale small mt-1 mb-3">
                <a href="{{ $link->url }}" {!! linkTarget() !!} class="url">{{ $link->url }}</a>
                <br>
                @lang('linkace.added_by'):
                <x-models.author :model="$link"/>
            </div>

            @if($link->description)
                <div class="description">{!! $link->formatted_description !!}</div>
            @endif
        </div>
    </div>
</div>
