@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "exerciseIDE") 

@section('content')
    @component('codearea2.exerciseNav', ['exercises' => $exercise->lesson->exercises(), 'current_exercise_id' => $exercise->id])
    @endcomponent

    <div id="codeIde">
        @component('codearea2.prompt', ['prompt' => $exercise->prompt])
        @endcomponent

        @component('codearea2.precode', ['pre_code' => $exercise->pre_code])
        @endcomponent

        @component('codearea2.testcode', ['test_code' => $exercise->test_code])
        @endcomponent

        @component('codearea2.codeEditor', ['start_code' => $exercise->start_code, 'item_type' => 'exercise', 'item_id' => $exercise->id])
        @endcomponent
    </div>
@endsection