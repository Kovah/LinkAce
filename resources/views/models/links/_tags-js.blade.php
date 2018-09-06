<script>
    $('#tags').selectize({
        delimiter: ',',
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input
            };
        },
        load: function (query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: '{{ route('ajax-tags') }}',
                type: 'post',
                data: {
                    'query': query,
                    '_token': '{{ csrf_token() }}'
                },
                dataType: 'json',
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res);
                }
            });
        }
    });
</script>
