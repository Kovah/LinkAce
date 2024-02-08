<ul class="link-list list-group">
    @foreach($links as $link)
        @include('models.links.partials.single-simple')
    @endforeach
</ul>
