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
        <div class="content">
            @yield('content')
        </div>
        <footer class="footer bg-primary pt-2">
            <ul class="nav">
                <li class="nav-item">
                    <a class="mx-2" href="{{ env('MIX_HOME_POLICY') }}">Privacy Policy</a>
                </li>
                <li class="nav-item nav-item-avtive">
                    <a class="mx-2" href="{{ env('MIX_HOME_DEVELOPER') }}">Developers</a>
                </li>
                <li class="nav-item nav-item-avtive">
                    <a class="mx-2" href="{{ env('MIX_HOME_TERMS') }}">Terms of Service</a>
                </li>
                <li class="nav-item nav-item-avtive">
                    <a class="mx-2" href="{{ env('MIX_HOME_CONTACT') }}">Contact Us</a>
                </li>
            </ul>
            <div class="text-center">
                Copyright © {{ date('Y') }} - <strong> {{ config('app.name') }} </strong>, All
                Rights Reserved
            </div>
        </footer>
    </div>
</body>

</html>
