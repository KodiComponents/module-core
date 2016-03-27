<ul class="navigation">
    @foreach($pages as $page)
        {!! $page->render() !!}
    @endforeach
</ul>
