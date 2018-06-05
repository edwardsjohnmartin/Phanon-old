@extends('layouts.app')

@section('scripts')
@component("scriptbundles/sculpt")
@endcomponent
@component("scriptbundles/codemirror")
@endcomponent
@endsection

@section('content')
<h2>New Sandbox</h2>
    @component("codearea/codeEditor")
    @endcomponent
@endsection