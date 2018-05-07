@extends('layouts.app')

@section('content')

    <h1 class="text-center">Sandbox</h1>

    <div>
        <button type="button" class="btn btn-default" id="runButton">Run</button>
        <textarea id="code"></textarea>
        <div id="mycanvas"></div>
        <pre id="output"></pre>
    </div>

    <script type="text/javascript" src="{{ asset('js/codewindow.js')}}"></script>

@endsection