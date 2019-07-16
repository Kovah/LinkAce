<div class="card mt-4">
    <div class="card-header">
        @lang('settings.api_token')
    </div>
    <div class="card-body">

        <p>@lang('settings.api_token_help')</p>

        <div class="input-group mb-3">
            <input type="text" id="api-token" class="form-control" value="{{ auth()->user()->api_token ?? '' }}"
                readonly aria-readonly="true" aria-label="@lang('settings.api_token_generate')"
                aria-describedby="api-token-generate">
            <div class="input-group-append">
                <button class="btn btn-outline-danger" type="button" id="api-token-generate">
                    <i class="fas fa-recycle mr-1"></i> @lang('settings.api_token_generate')
                </button>
            </div>
        </div>

        <p id="api-token-generate-failure" class="small text-danger" style="display:none">
            @lang('settings.api_token_generate_failure')
        </p>

        <p class="small text-warning">@lang('settings.api_token_generate_info')</p>

    </div>
</div>

@push('scripts')
    <script>
        $('#api-token-generate').click(function (e) {
            var $btn = $(e.currentTarget);
            $btn.prop('disabled', true);

            if (confirm('@lang('settings.api_token_generate_confirm')')) {

                $.ajax({
                    method: 'POST',
                    url: '{{ route('generate-api-token') }}',
                    dataType: 'json',
                    data: {_token: '{{ csrf_token() }}'}
                }).done(function (response) {
                    if (typeof response.new_token !== 'undefined') {
                        $('#api-token').val(response.new_token);

                        window.setTimeout(function () {
                            $btn.prop('disabled', false);
                        }, 5000);
                    } else {
                        $('#api-token-generate-failure').show();
                    }
                }).fail(function () {
                    $('#api-token-generate-failure').show();
                });

            } else {
                $btn.prop('disabled', false);
            }
        });
    </script>
@endpush
