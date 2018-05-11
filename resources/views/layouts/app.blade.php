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
    <link rel="stylesheet" href="{{ asset('codemirror/lib/codemirror.css')}}">
    <link rel="stylesheet" href="{{ asset('codemirror/addon/fold/foldgutter.css')}}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css')}}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('skulpt/skulpt.min.js') }}"></script>
    <script src="{{ asset('skulpt/skulpt-stdlib.js') }}"></script>
    <script src="{{ asset('jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('codemirror/addon/edit/matchbrackets.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/foldcode.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/foldgutter.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/brace-fold.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/comment-fold.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/indent-fold.js') }}"></script>
    <script src="{{ asset('codemirror/addon/fold/markdown-fold.js') }}"></script>
    <script src="{{ asset('codemirror/addon/search/jump-to-line.js') }}"></script>
    <script src="{{ asset('codemirror/mode/python/python.js')}}"></script>
    <script src="{{ asset('codemirror/addon/edit/closebrackets.js')}}"></script>
    <script src="{{ asset('js/bootstrap-multiselect.js')}}"></script>

</head>
<body>
    <div id="app">
        @include('inc.navbar')
        <div class="container">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>
</body>
</html>
