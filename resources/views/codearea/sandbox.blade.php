@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "sandboxIDE") 

@section('content')
    <h2>Sandbox</h2>

    @component('codearea.sandboxExamples')
    @endcomponent

    <div id="codeIde" class="fullIDE">
        @component('codearea.codeEditor', ['start_code' => '', 'item_type' => '', 'item_id' => ''])
        @endcomponent
    </div>

    <input type="hidden" id="pre_code" value='Some value' />
@endsection
