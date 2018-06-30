@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "sandboxIDE") 

@section('content')
    <h2>Sandbox</h2>

    @component('codearea2.sandboxExamples')
    @endcomponent

    <div id="codeIde" class="fullIDE">
        @component('codearea2.codeEditor', ['start_code' => '', 'item_type' => '', 'item_id' => ''])
        @endcomponent
    </div>
@endsection
