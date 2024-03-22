<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <link rel="stylesheet"
        href="{{ app()->environment('production') ? secure_asset('css/app.css') : asset('css/app.css') }}">

    <script src="{{ app()->environment('production') ? secure_asset('js/app.js') : asset('js/app.js') }}" defer></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>

<body>
    <div class="wrapper">
        @yield('content')
    </div>
</body>

</html>
