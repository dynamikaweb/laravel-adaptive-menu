<section id="{{ $id }}" class="dynamika-menu-nav-forest">
    <ul class="dynamika-menu-ul-forest">
        @foreach($normalizedItems as $root)
            @include('adaptive-menu::partials.menu-root', ['item' => $root])
        @endforeach
    </ul>
</section>
