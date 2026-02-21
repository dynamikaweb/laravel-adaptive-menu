<li class="dynamika-menu-li-root">
    <a class="dynamika-menu-a-root" href="{{ $item['url'] }}" title="{{ strip_tags($item['label']) }}" target="{{ $item['target'] }}">
        {!! $item['label'] !!}
    </a> 
    
    @if(!empty($item['items']))
        <span class="dynamika-menu-span-items">
            @foreach($item['items'] as $sub)
                @include('adaptive-menu::partials.menu-sub', ['item' => $sub])
            @endforeach
        </span>
    @endif
</li>