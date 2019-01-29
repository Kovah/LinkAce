<script>
    $('#only_tags').selectize({
        delimiter: ',',
        persist: false,
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
