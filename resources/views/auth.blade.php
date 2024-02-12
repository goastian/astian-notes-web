<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="favicon.png" type="image/png">


    <!-- Fonts -->
    <link rel="stylesheet"
        href="{{ app()->environment('production') ? secure_asset('css/app.css') : asset('css/app.css') }}">

    <script src="{{ app()->environment('production') ? secure_asset('js/app.js') : asset('js/app.js') }}" defer></script>


</head>

<body class="font-sans antialiased">

    <div class="container">
        <div class="card mt-5">
            <div class="card-head mb-2 text-center fw-bold h1 text-secondary">
                Login
            </div>
            <div class="card-body text-center text-capitalize mt-5">
                <form action="/redirect" method="GET">
                    <button type="submit" class=" btn px-4 py-2 btn-primary">
                        Login with Astian
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
