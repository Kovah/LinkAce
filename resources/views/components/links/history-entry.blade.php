@foreach($changes as $change)
    <div class="link-history-entry mb-1">{{ $timestamp }}: {!! $change !!}</div>
@endforeach
