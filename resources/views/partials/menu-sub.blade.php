<span class="dynamika-menu-span-sub">
    @if(!empty($item['label']))
        <a href="{{ $item['url'] }}">
            <h3>{!! $item['label'] !!}</h3>
        </a>
    @endif

    @if(!empty($item['content']))
        <p>{!! $item['content'] !!}</p>
    @endif

    <ul>
        @foreach($item['items'] as $link)
            @include('adaptive-menu::partials.menu-link', ['item' => $link])
        @endforeach
    </ul>
</span>