@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "sandboxIDE") 

@section('content')
    <h2>Sandbox</h2>

    @component('codearea.sandboxExamples')
    @endcomponent

    <div id="codeIde" class="fullIDE">
        @component('codearea.codeEditor', [
            'role' => null,
            'item_type' => 'sandbox', 
            'item' => null,
            'initial_editor_code' => null,
            'messages' => []
        ])
        @endcomponent
    </div>
@endsection
