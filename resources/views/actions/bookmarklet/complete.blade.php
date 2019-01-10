@extends('layouts.bookmarklet')

@section('content')

    <div class="mt-3">
        <p>@lang('linkace.bookmarklet_close')</p>

        <a href="{{ route('front') }}" target="_blank" class="btn btn-primary">
            <i class="fa fa-arrow-left fa-mr"></i>
            @lang('linkace.open_linkace')
        </a>
    </div>

@endsection

@push('scripts')
    <script>
        var timer = $('.bm-timer');
        window.setInterval(function () {
            timer.text(parseInt(timer.text()) - 1);
        }, 1000);
        window.setTimeout(function () {
            window.close();
        }, 5000);
    </script>
@endpush
