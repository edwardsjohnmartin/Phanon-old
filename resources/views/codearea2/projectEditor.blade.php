@extends('layouts.app')

@section('content')
    <div id="codeIde">
        @component('codearea2.prompt', ['prompt' => $project->prompt])
        @endcomponent

        @component('codearea2.precode', ['pre_code' => $project->pre_code])
        @endcomponent

        @component('codearea2.codeEditor', ['item_type' => 'project', 'item_id' => $project->id])
        @endcomponent
    </div>
@endsection