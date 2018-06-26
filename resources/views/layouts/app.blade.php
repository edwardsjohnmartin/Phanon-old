<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/site.css')}}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css')}}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-multiselect.js')}}"></script>
    <script src="{{ asset('js/codewindow.js')}}"></script>
    @yield('scripts')
</head>
<body class="@yield("bodyTag")">
    <div id="app">
        @include('inc.navbar')
        <div class="container-fluid">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>
    @yield('scripts-end')
</body>
</html>
