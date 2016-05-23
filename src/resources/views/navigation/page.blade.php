@if($hasChild)
    <li {!! $attributes !!}>
        <a href="#">
            {!! $icon !!}
            <span class="mm-text">{!! $title !!}</span>
        </a>

        <ul>
            @foreach($pages as $page)
                {!! $page->render() !!}
            @endforeach
        </ul>
    </li>
@else
    <li {!! $attributes !!}>
        <a href="{{ $url }}">
            {!! $icon !!}
            <span class="mm-text">{!! $title !!}</span>
            {!! $badge !!}
        </a>
    </li>
@endif