@foreach($changes as $change)
    <div class="history-entry mb-1">{{ $timestamp }}: {!! $change !!}</div>
@endforeach
