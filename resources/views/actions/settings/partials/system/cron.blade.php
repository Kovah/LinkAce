<div class="card">
    <div class="card-header">
        @lang('settings.cron_token')
    </div>
    <div class="card-body">

        <p>@lang('settings.cron_token_help')</p>

        <div class="input-group mb-3">
            <input type="text" id="cron-token" class="form-control" value="{{ systemsettings('cron_token') }}"
                readonly aria-readonly="true" aria-label="@lang('settings.cron_token_generate')"
                aria-describedby="cron-token-generate">
            <div class="input-group-append">
                <button class="btn btn-outline-danger" type="button" id="cron-token-generate">
                    <i class="fas fa-recycle mr-1"></i> @lang('settings.cron_token_generate')
                </button>
            </div>
        </div>

        <p id="cron-token-generate-failure" class="small text-danger" style="display:none">
            @lang('settings.cron_token_generate_failure')
        </p>

        <p class="small text-warning">@lang('settings.cron_token_generate_info')</p>

        @if(systemsettings('cron_token'))
            <p>
                @lang('settings.cron_token_url', [
                    'route' => route('cron', ['token' => systemsettings('cron_token')])
                ])
            </p>
        @endif

    </div>
</div>

@push('scripts')
    <script>
        $('#cron-token-generate').click(function (e) {
            var $btn = $(e.currentTarget);
            var $cronUrl = $('#cron-url');

            $btn.prop('disabled', true);
            $cronUrl.text('');

            if (confirm('@lang('settings.cron_token_generate_confirm')')) {

                $.ajax({
                    method: 'POST',
                    url: '{{ route('generate-cron-token') }}',
                    dataType: 'json',
                    data: {_token: '{{ csrf_token() }}'}
                }).done(function (response) {
                    if (typeof response.new_token !== 'undefined') {
                        $('#cron-token').val(response.new_token);
                        $cronUrl.text('{{ route('cron', ['token' => '']) }}/' + response.new_token);

                        window.setTimeout(function () {
                            $btn.prop('disabled', false);
                        }, 5000);
                    } else {
                        $('#cron-token-generate-failure').show();
                    }
                }).fail(function () {
                    $('#cron-token-generate-failure').show();
                });

            } else {
                $btn.prop('disabled', false);
            }
        });
    </script>
@endpush
