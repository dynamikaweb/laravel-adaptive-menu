{!! $content !!}

@push('css')
    @if(file_exists(public_path('vendor/dynamikasolucoesweb/adaptive-menu/css/style.css')))
        <link rel="stylesheet" href="{{ asset('vendor/dynamikasolucoesweb/adaptive-menu/css/style.css') }}">
    @else
        <style>
            {!! file_get_contents(base_path('vendor/dynamikasolucoesweb/laravel-adaptive-menu/resources/assets/css/style.css')) !!}
        </style>
    @endif
@endpush

@push('scripts')
    @if(file_exists(public_path('vendor/dynamikasolucoesweb/adaptive-menu/js/script.js')))
        <script src="{{ asset('vendor/dynamikasolucoesweb/adaptive-menu/js/script.js') }}"></script>
    @else
        <script>
            {!! file_get_contents(base_path('vendor/dynamikasolucoesweb/laravel-adaptive-menu/resources/assets/js/script.js')) !!}
        </script>
    @endif
@endpush