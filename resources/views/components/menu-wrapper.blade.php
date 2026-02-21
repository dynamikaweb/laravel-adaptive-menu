{!! $content !!}

@push('css')
    @if(file_exists(public_path('vendor/dynamikaweb/adaptive-menu/css/style.css')))
        <link rel="stylesheet" href="{{ asset('vendor/dynamikaweb/adaptive-menu/css/style.css') }}">
    @else
        <style>
            {!! file_get_contents(base_path('vendor/dynamikaweb/laravel-adaptive-menu/resources/assets/css/style.css')) !!}
        </style>
    @endif
@endpush

@push('scripts')
    @if(file_exists(public_path('vendor/dynamikaweb/adaptive-menu/js/script.js')))
        <script src="{{ asset('vendor/dynamikaweb/adaptive-menu/js/script.js') }}"></script>
    @else
        <script>
            {!! file_get_contents(base_path('vendor/dynamikaweb/laravel-adaptive-menu/resources/assets/js/script.js')) !!}
        </script>
    @endif
@endpush